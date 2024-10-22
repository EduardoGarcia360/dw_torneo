<?= $this->extend('layout'); ?>

<?= $this->section('contenido'); ?>
<h3 class="my-3">Agregar Incidencia</h3>

<?php if (session()->getFlashdata('error') !== null) { ?>
    <div class="alert alert-danger">
        <?= session()->getFlashdata('error'); ?>
    </div>
<?php } ?>

<form action="<?= base_url('incidencias'); ?>" method="post">
    <div class="form-group mb-3">
        <label for="jugador_id">Jugador</label>
        <select class="form-control" id="jugador_id" name="jugador_id" required>
            <option value="">Seleccione un jugador</option>
            <?php foreach ($jugadores as $jugador) : ?>
                <option value="<?= $jugador['id']; ?>">
                    <?= $jugador['nombres'] . ' ' . $jugador['apellidos']; ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="form-group mb-3">
        <label for="descripcion">Descripción</label>
        <textarea class="form-control" id="descripcion" name="descripcion" rows="3" required><?= old('descripcion'); ?></textarea>
    </div>

    <div class="form-group mb-3">
        <label for="tipo_tarjeta">Tipo de Tarjeta</label>
        <select class="form-control" id="tipo_tarjeta" name="tipo_tarjeta" required onchange="toggleFechaSuspension()">
            <option value="A">Amarilla</option>
            <option value="R">Roja</option>
        </select>
    </div>

    <div class="form-group mb-3">
        <label for="fecha_incidencia">Fecha de Incidencia</label>
        <input type="date" class="form-control" id="fecha_incidencia" name="fecha_incidencia" value="<?= old('fecha_incidencia'); ?>" required>
    </div>

    <div class="form-group mb-3">
        <label for="fecha_suspension">Fecha de Suspensión</label>
        <input type="date" class="form-control" id="fecha_suspension" name="fecha_suspension" value="<?= old('fecha_suspension'); ?>" disabled>
    </div>
    
    <button type="submit" class="btn btn-primary">Guardar</button>
    <a href="<?= base_url('incidencias'); ?>" class="btn btn-secondary">Cancelar</a>
</form>

<script>
    // Función para habilitar/deshabilitar el campo de fecha de suspensión
    function toggleFechaSuspension() {
        var tipoTarjeta = document.getElementById('tipo_tarjeta').value;
        var fechaSuspension = document.getElementById('fecha_suspension');

        if (tipoTarjeta === 'R') {
            fechaSuspension.disabled = false;
            fechaSuspension.required = true;
        } else {
            fechaSuspension.disabled = true;
            fechaSuspension.required = false;
            fechaSuspension.value = ''; // Limpiar el valor si es Amarilla
        }
    }

    // Inicializar el estado del campo cuando se carga la página
    document.addEventListener('DOMContentLoaded', function() {
        toggleFechaSuspension();
    });
</script>

<?= $this->endSection(); ?>
