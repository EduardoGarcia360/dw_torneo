<?= $this->extend('layout'); ?>
<?= $this->section('contenido'); ?>
<h3 class="my-3" id="titulo"><?php echo '¡Bienvenido, '.session('nombre').' '.session('apellido').'!'; ?></h3>
<?= $this->endSection(); ?>