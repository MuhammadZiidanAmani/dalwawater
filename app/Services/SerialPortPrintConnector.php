<?php

namespace App\Services;

use Mike42\Escpos\PrintConnectors\PrintConnector;
use RuntimeException;

class SerialPortPrintConnector implements PrintConnector
{
    private string $buffer = '';

    private bool $finalized = false;

    public function __construct(
        private readonly string $port,
        private readonly int $baud = 9600,
    ) {
        if (! preg_match('/^COM\d+$/i', $this->port)) {
            throw new RuntimeException("Nama port {$this->port} tidak valid.");
        }
    }

    public function __destruct()
    {
        if (! $this->finalized) {
            trigger_error('Print connector belum ditutup. Apakah printer->close() sudah dipanggil?', E_USER_NOTICE);
        }
    }

    public function finalize(): void
    {
        if ($this->finalized) {
            return;
        }

        $dataFile = tempnam(sys_get_temp_dir(), 'escpos-data-');
        $scriptFile = tempnam(sys_get_temp_dir(), 'escpos-serial-').'.ps1';

        if ($dataFile === false || $scriptFile === false) {
            throw new RuntimeException('Gagal membuat file sementara untuk cetak serial.');
        }

        file_put_contents($dataFile, $this->buffer);
        file_put_contents($scriptFile, $this->powershellScript($dataFile));

        $command = 'powershell -NoProfile -ExecutionPolicy Bypass -File '.escapeshellarg($scriptFile);
        exec($command, $output, $exitCode);

        @unlink($dataFile);
        @unlink($scriptFile);

        $this->finalized = true;

        if ($exitCode !== 0) {
            throw new RuntimeException('Gagal mengirim data ke '.$this->port.': '.trim(implode(' ', $output)));
        }
    }

    public function read(int $len): bool|string
    {
        return false;
    }

    public function write(string $data): void
    {
        if ($this->finalized) {
            throw new RuntimeException("Port {$this->port} sudah tertutup.");
        }

        $this->buffer .= $data;
    }

    private function powershellScript(string $dataFile): string
    {
        $port = $this->psQuote(strtoupper($this->port));
        $file = $this->psQuote($dataFile);
        $baud = $this->baud;

        return <<<POWERSHELL
\$ErrorActionPreference = 'Stop'
\$bytes = [System.IO.File]::ReadAllBytes($file)
\$serial = New-Object System.IO.Ports.SerialPort $port, $baud, 'None', 8, 'One'
\$serial.WriteTimeout = 5000
\$serial.ReadTimeout = 5000
\$serial.Open()
\$serial.Write(\$bytes, 0, \$bytes.Length)
\$serial.Close()
POWERSHELL;
    }

    private function psQuote(string $value): string
    {
        return "'".str_replace("'", "''", $value)."'";
    }
}
