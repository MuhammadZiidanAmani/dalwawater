<?php

namespace App\Services;

use App\Models\AppSetting;
use App\Models\Transaction;
use InvalidArgumentException;
use Mike42\Escpos\PrintConnectors\FilePrintConnector;
use Mike42\Escpos\PrintConnectors\NetworkPrintConnector;
use Mike42\Escpos\PrintConnectors\PrintConnector;
use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;
use Mike42\Escpos\Printer;
use RuntimeException;

class ThermalReceiptPrinter
{
    public function print(Transaction $transaction, bool $force = false): void
    {
        if (! $force && ! config('thermal.auto_print')) {
            return;
        }

        $transaction->loadMissing(['user', 'details.product']);

        $printer = new Printer($this->connector());

        try {
            $this->writeReceipt($printer, $transaction);
        } finally {
            $printer->close();
        }
    }

    private function connector(): PrintConnector
    {
        $connector = strtolower((string) config('thermal.connector', 'windows'));
        $printerName = (string) config('thermal.printer_name', '');

        return match ($connector) {
            'network' => $this->networkConnector(),
            'serial' => $this->serialConnector($printerName),
            'file' => new FilePrintConnector($printerName),
            'windows' => preg_match('/^COM\d+$/i', $printerName) ? $this->serialConnector($printerName) : $this->windowsConnector($printerName),
            default => throw new InvalidArgumentException("Konektor printer thermal '{$connector}' tidak didukung."),
        };
    }

    private function serialConnector(string $printerName): SerialPortPrintConnector
    {
        if ($printerName === '') {
            throw new RuntimeException('THERMAL_PRINTER_NAME belum diisi.');
        }

        return new SerialPortPrintConnector($printerName, (int) config('thermal.baud', 9600));
    }

    private function networkConnector(): NetworkPrintConnector
    {
        $ip = config('thermal.ip');

        if (! $ip) {
            throw new RuntimeException('THERMAL_PRINTER_IP belum diisi.');
        }

        return new NetworkPrintConnector((string) $ip, (int) config('thermal.port', 9100));
    }

    private function windowsConnector(string $printerName): WindowsPrintConnector
    {
        if ($printerName === '') {
            throw new RuntimeException('THERMAL_PRINTER_NAME belum diisi.');
        }

        return new WindowsPrintConnector($printerName);
    }

    private function writeReceipt(Printer $printer, Transaction $transaction): void
    {
        $settings = AppSetting::receiptSettings();
        $width = max(24, min(42, (int) ($settings['receipt_paper_width'] ?? config('thermal.width', 32))));
        $subtotal = (int) $transaction->details->sum(fn ($detail): int => (int) $detail->harga * (int) $detail->qty);

        $printer->initialize();
        $printer->setJustification(Printer::JUSTIFY_CENTER);
        $printer->setEmphasis(true);
        $printer->text($this->plain((string) $settings['receipt_store_name'])."\n");
        $printer->setEmphasis(false);

        foreach ([
            $settings['receipt_address_line_1'] ?? '',
            $settings['receipt_address_line_2'] ?? '',
        ] as $line) {
            foreach ($this->wrap((string) $line, $width) as $wrappedLine) {
                if ($wrappedLine !== '') {
                    $printer->text($wrappedLine."\n");
                }
            }
        }

        if ($settings['receipt_phone'] ?? '') {
            $printer->text($this->plain((string) $settings['receipt_phone'])."\n");
        }

        $printer->text($this->divider($width));

        $printer->setJustification(Printer::JUSTIFY_LEFT);
        $printer->text($this->row('No', $transaction->kode_transaksi, $width));
        $printer->text($this->row('Tanggal', $transaction->created_at->format('d/m/Y H:i'), $width));

        if (AppSetting::enabled($settings, 'receipt_show_cashier')) {
            $printer->text($this->row('Kasir', $transaction->user?->name ?? '-', $width));
        }

        if (AppSetting::enabled($settings, 'receipt_show_customer') && $transaction->customer_name) {
            $printer->text($this->row('Pembeli', $transaction->customer_name, $width));
        }

        if ($transaction->isVoid()) {
            $printer->text($this->row('Status', 'BATAL', $width));
        }

        $printer->text($this->divider($width));

        foreach ($transaction->details as $detail) {
            $name = $detail->product?->nama_barang ?? 'Produk dihapus';
            foreach ($this->wrap($name, $width) as $line) {
                $printer->text($line."\n");
            }

            $qtyPrice = $detail->qty.' x '.$this->money((int) $detail->harga);
            $printer->text($this->row($qtyPrice, $this->money((int) $detail->subtotal), $width));
        }

        $printer->text($this->divider($width));
        $printer->text($this->row('Sub Total', $this->money($subtotal), $width));

        if ((int) $transaction->discount_total > 0) {
            $printer->text($this->row('Diskon', $this->money((int) $transaction->discount_total), $width));
        }

        $printer->setEmphasis(true);
        $printer->text($this->row('Total', $this->money((int) $transaction->total), $width));
        $printer->setEmphasis(false);

        if (AppSetting::enabled($settings, 'receipt_show_payment')) {
            $printer->text($this->row('Status', $transaction->payment_status === 'paid' ? 'LUNAS' : 'BELUM BAYAR', $width));
            $printer->text($this->row('Metode', $this->paymentType($transaction->payment_type), $width));

            if ($transaction->payment_status === 'paid') {
                $printer->text($this->row('Diterima', $this->money((int) $transaction->uang_diterima), $width));
                $printer->text($this->row('Kembali', $this->money((int) $transaction->kembalian), $width));
            }
        }

        $printer->text($this->divider($width));
        $printer->setJustification(Printer::JUSTIFY_CENTER);

        foreach ([
            $settings['receipt_footer_line_1'] ?? '',
            $settings['receipt_footer_line_2'] ?? '',
            $settings['receipt_footer_line_3'] ?? '',
        ] as $line) {
            foreach ($this->wrap((string) $line, $width) as $wrappedLine) {
                if ($wrappedLine !== '') {
                    $printer->text($wrappedLine."\n");
                }
            }
        }

        $printer->feed(3);
    }

    private function row(string $left, string $right, int $width): string
    {
        $left = $this->plain($left);
        $right = $this->plain($right);
        $space = max(1, $width - strlen($left) - strlen($right));

        if (strlen($left) + strlen($right) >= $width) {
            return $left."\n".str_pad($right, $width, ' ', STR_PAD_LEFT)."\n";
        }

        return $left.str_repeat(' ', $space).$right."\n";
    }

    private function divider(int $width): string
    {
        return str_repeat('-', $width)."\n";
    }

    /**
     * ESC/POS 58mm printer is safest with plain ASCII text.
     */
    private function plain(string $value): string
    {
        $value = str_replace(['–', '—'], '-', $value);
        $value = iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $value);

        return trim((string) $value);
    }

    /**
     * @return array<int, string>
     */
    private function wrap(string $value, int $width): array
    {
        return explode("\n", wordwrap($this->plain($value), $width, "\n", true));
    }

    private function money(int $value): string
    {
        return 'Rp '.number_format($value, 0, ',', '.');
    }

    private function paymentType(?string $type): string
    {
        return match ($type) {
            'cash' => 'Tunai',
            'transfer' => 'Transfer',
            default => '-',
        };
    }
}
