<?php $__env->startSection('title', 'Nota '.$transaction->kode_transaksi); ?>

<?php $__env->startSection('content'); ?>
<main class="wrap">
    <div>
        <div class="actions">
            <a class="btn" href="<?php echo e(route('dashboard', ['page' => 'reports'])); ?>">Kembali</a>
            <button class="btn primary" onclick="window.print()">Print Nota</button>
        </div>
        <section class="receipt">
            <h3>Dalwa Water Tegal</h3>
            <p>Jl. Raya Tegalwangi, Rt. 13/05<br>Kec. Talang - Kab. Tegal<br>CP : 0813-9375-0612</p>
            <hr>
            <div class="line"><span>No</span><strong><?php echo e($transaction->kode_transaksi); ?></strong></div>
            <div class="line"><span>Tanggal</span><span><?php echo e($transaction->created_at->format('d/m/Y H:i')); ?></span></div>
            <div class="line"><span>Kasir</span><span><?php echo e($transaction->user->name); ?></span></div>
            <hr>
            <?php $totalDiscount = 0; ?>
            <?php $__currentLoopData = $transaction->details; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $detail): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php
                    $normalSubtotal = $detail->harga * $detail->qty;
                    $discount = max(0, $normalSubtotal - $detail->subtotal);
                    $totalDiscount += $discount;
                ?>
                <div class="line"><span><?php echo e($detail->product->nama_barang); ?></span><span><?php echo e($detail->qty); ?> x <?php echo e(number_format($detail->harga, 0, ',', '.')); ?></span></div>
                <div class="line"><span></span><strong><?php echo e(number_format($detail->subtotal, 0, ',', '.')); ?></strong></div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            <hr>
            <div class="line"><span>Total</span><strong><?php echo e(number_format($transaction->total, 0, ',', '.')); ?></strong></div>
            <?php if($totalDiscount > 0): ?>
                <div class="line"><span>Diskon</span><span><?php echo e(number_format($totalDiscount, 0, ',', '.')); ?></span></div>
            <?php endif; ?>
            <div class="line"><span>Metode</span><span><?php echo e($transaction->payment_type === 'cash' ? 'Tunai' : 'Transfer'); ?></span></div>
            <?php if($transaction->payment_type === 'transfer'): ?>
                <div class="line"><span>Bank</span><span><?php echo e($transaction->bank_name ?: '-'); ?></span></div>
                <div class="line"><span>Ref</span><span><?php echo e($transaction->reference_number ?: '-'); ?></span></div>
            <?php endif; ?>
            <div class="line"><span>Diterima</span><span><?php echo e(number_format($transaction->uang_diterima, 0, ',', '.')); ?></span></div>
            <div class="line"><span>Kembali</span><strong><?php echo e(number_format($transaction->kembalian, 0, ',', '.')); ?></strong></div>
            <hr>
            <p>Terima kasih<br>Barang yang sudah dibeli tidak dapat ditukar/dikembalikan</p>
        </section>
    </div>
</main>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.print', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>