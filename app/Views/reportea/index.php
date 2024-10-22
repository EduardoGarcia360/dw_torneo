<?= $this->extend('layout'); ?>

<?= $this->section('contenido'); ?>
<h3 class="my-3">Reporte de Equipos y Jugadores para el Árbitro</h3>
<p>Jornada Actual: <?= $jornadaActual; ?></p>

<!-- Combo para seleccionar el equipo -->
<form method="get" action="<?= base_url('reportea'); ?>">
    <div class="form-group mb-3">
        <label for="equipo_id">Seleccionar Equipo</label>
        <select class="form-control" id="equipo_id" name="equipo_id" onchange="this.form.submit()">
            <option value="">Seleccione un equipo</option>
            <?php foreach ($equipos as $equipo): ?>
                <option value="<?= $equipo['id']; ?>" <?= ($equipo['id'] == $equipoSeleccionado) ? 'selected' : ''; ?>>
                    <?= $equipo['nombre_equipo']; ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>
</form>

<a href="<?= base_url('reportea/generarReportePdf?equipo_id='.$equipoSeleccionado); ?>" class="btn btn-primary mb-3">Generar Reporte en PDF</a>

<!-- Mostrar los jugadores del equipo seleccionado -->
<?php if (!empty($jugadores)): ?>
    <h4>Equipo: <?= $equipoSeleccionado ? $equipos[array_search($equipoSeleccionado, array_column($equipos, 'id'))]['nombre_equipo'] : ''; ?></h4>
    <table class="table table-hover table-bordered">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Fotografía</th>
                <th>Nombres</th>
                <th>Apellidos</th>
                <th>Fecha de Nacimiento</th>
                <th>Suspensión</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($jugadores as $jugador): ?>
                <tr>
                    <td><?= $jugador['id']; ?></td>
                    <td>
                        <?php if (!empty($jugador['fotografia'])): ?>
                            <img src="<?= base_url('uploads/jugadores/'.$jugador['fotografia']); ?>" alt="Foto del Jugador" class="img-thumbnail" width="50" height="50">
                        <?php else: ?>
                            Sin fotografía
                        <?php endif; ?>
                    </td>
                    <td><?= $jugador['nombres']; ?></td>
                    <td><?= $jugador['apellidos']; ?></td>
                    <td><?= $jugador['fecha_nacimiento']; ?></td>
                    <td>
                        <?php if ($jugador['suspendido']): ?>
                            <span class="text-danger">Suspendido</span>
                        <?php else: ?>
                            Disponible
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php else: ?>
    <p>No se han encontrado jugadores para el equipo seleccionado.</p>
<?php endif; ?>

<?= $this->endSection(); ?>
