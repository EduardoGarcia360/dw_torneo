<?= $this->extend('layout'); ?>

<?= $this->section('contenido'); ?>
<h3 class="my-3">Editar Jugador</h3>

<?php if (session()->getFlashdata('error') !== null) { ?>
    <div class="alert alert-danger">
        <?= session()->getFlashdata('error'); ?>
    </div>
<?php } ?>

<form action="<?= base_url('jugadores/'.$jugador['id']); ?>" method="post" enctype="multipart/form-data">
    <input type="hidden" name="_method" value="PUT">
    <input type="hidden" name="jugador_id" value="<?= $jugador['id']; ?>">

    <div class="form-group mb-3">
        <label for="nombres">Nombres</label>
        <input type="text" class="form-control" id="nombres" name="nombres" value="<?= old('nombres', $jugador['nombres']); ?>" required>
    </div>
    <div class="form-group mb-3">
        <label for="apellidos">Apellidos</label>
        <input type="text" class="form-control" id="apellidos" name="apellidos" value="<?= old('apellidos', $jugador['apellidos']); ?>" required>
    </div>
    <div class="form-group mb-3">
        <label for="fecha_nacimiento">Fecha de Nacimiento</label>
        <input type="date" class="form-control" id="fecha_nacimiento" name="fecha_nacimiento" value="<?= old('fecha_nacimiento', $jugador['fecha_nacimiento']); ?>" required>
    </div>
    <div class="form-group mb-3">
        <label for="equipo_id">Equipo</label>
        <select class="form-control" id="equipo_id" name="equipo_id" required>
            <option value="">Seleccione un equipo</option>
            <?php foreach ($equipos as $equipo) : ?>
                <option 
                    value="<?= $equipo['id']; ?>" 
                    <?php echo($equipo['id'] == $jugador['equipo_id']) ? 'selected' : ''; ?>
                >
                    <?= $equipo['nombre_equipo']; ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="form-group mb-3">
        <label for="fotografia">Fotografía</label>
        <?php if (!empty($jugador['fotografia'])): ?>
            <div class="mb-2">
                <img src="<?= base_url('uploads/jugadores/'.$jugador['fotografia']); ?>" alt="Fotografía del Jugador" class="img-thumbnail" width="150">
            </div>
        <?php endif; ?>
        <input type="file" class="form-control" id="fotografia" name="fotografia">
    </div>
    
    <button type="submit" class="btn btn-primary">Guardar Cambios</button>
    <a href="<?= base_url('jugadores'); ?>" class="btn btn-secondary">Cancelar</a>
</form>

<?= $this->endSection(); ?>
