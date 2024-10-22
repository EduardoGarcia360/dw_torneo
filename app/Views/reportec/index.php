<?= $this->extend('layout'); ?>

<?= $this->section('contenido'); ?>
<h3 class="my-3">Reporte de Goleadores</h3>

<!-- Botón para generar el PDF -->
<form method="get" action="<?= base_url('reportec/generar_pdf'); ?>" target="_blank">
    <button type="submit" class="btn btn-primary">Generar PDF</button>
</form>

<!-- Tabla para mostrar los goleadores -->
<table class="table table-hover table-bordered">
    <thead class="table-dark">
        <tr>
            <th scope="col">Fotografía</th>
            <th scope="col">Jugador</th>
            <th scope="col">Equipo</th>
            <th scope="col">Goles Totales</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($goleadores as $goleador): ?>
            <tr>
                <td>
                    <?php if (!empty($goleador['fotografia'])): ?>
                        <img src="<?= base_url('uploads/jugadores/' . $goleador['fotografia']); ?>" alt="Foto del Jugador" class="img-thumbnail" width="50" height="50">
                    <?php else: ?>
                        Sin fotografía
                    <?php endif; ?>
                </td>
                <td><?= $goleador['jugador_nombre'] . ' ' . $goleador['jugador_apellidos']; ?></td>
                <td><?= $goleador['nombre_equipo']; ?></td>
                <td><?= $goleador['total_goles']; ?></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<?= $this->endSection(); ?>
