<style>
	.wpk-log-container {
		height: 350px;
		margin: 20px 0;
		overflow: auto;
		background: #f6f6f6;
		border: 1px solid #bcaaaa;
	}

	.wpk-log {
		font-weight: 1.25em;
		padding: 5px;
	}

	.wpk-error .wpk-log-message {
		color: #bc2a2a;
		font-weight: bold;
	}
</style>

<div class="wpk-log-container">

    <?php $__currentLoopData = $logs;
    $__env->addLoop( $__currentLoopData );
    foreach ( $__currentLoopData as $data ): $__env->incrementLoopIndices();
        $loop = $__env->getLastLoop(); ?>
		<div class="wpk-log wpk-<?php echo e( $data[ 'type' ] ); ?>">
			<strong class="wpk-timestamp"><?php echo e( $data[ 'date' ] ); ?></strong>
			<span class="wpk-log-message"> - <?php echo $data[ 'log' ]; ?></span>
		</div>
    <?php endforeach;
    $__env->popLoop();
    $loop = $__env->getLastLoop(); ?>

</div>
<?php /**PATH /var/www/html/wp-content/plugins/public/views/admin/logs.blade.php ENDPATH**/ ?>
