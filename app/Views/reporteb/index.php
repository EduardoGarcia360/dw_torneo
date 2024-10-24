<?= $this->extend('layout'); ?>

<?= $this->section('contenido'); ?>
<h3 class="my-3">Reporte de Incidencias de Jugadores</h3>

<!-- Combo para seleccionar el equipo -->
<div class="form-group mb-3">
    <label for="equipo_id">Seleccionar Equipo</label>
    <select class="form-control" id="equipo_id">
        <option value="">Todos los equipos</option>
        <?php foreach ($equipos as $equipo): ?>
            <option value="<?= $equipo['id']; ?>">
                <?= $equipo['nombre_equipo']; ?>
            </option>
        <?php endforeach; ?>
    </select>
</div>

<!-- Combo para seleccionar el jugador -->
<div class="form-group mb-3">
    <label for="jugador_id">Seleccionar Jugador</label>
    <select class="form-control" id="jugador_id" disabled>
        <option value="">Todos los jugadores con incidencia</option>
    </select>
</div>

<!-- Botón para generar el PDF -->
<form id="form-pdf" method="get" action="<?= base_url('reporteb/generar_pdf'); ?>" target="_blank">
    <input type="hidden" id="equipo_pdf" name="equipo_id">
    <input type="hidden" id="jugador_pdf" name="jugador_id">
    <button type="submit" class="btn btn-primary">Generar PDF</button>
</form>

<!-- Mostrar las incidencias de los jugadores -->
<h4>Incidencias de Jugadores</h4>
<table class="table table-hover table-bordered">
    <thead class="table-dark">
        <tr>
            <th>ID</th>
            <th>Fotografía</th>
            <th>Jugador</th>
            <th>Descripción</th>
            <th>Tipo de Tarjeta</th>
            <th>Fecha de Incidencia</th>
            <th>Fecha de Suspensión</th>
        </tr>
    </thead>
    <tbody id="tabla-incidencias">
        <?php foreach ($incidencias as $incidencia): ?>
            <tr data-equipo="<?= $incidencia['equipo_id']; ?>" data-jugador="<?= $incidencia['jugador_id']; ?>">
                <td><?= $incidencia['id']; ?></td>
                <td>
                    <?php if (!empty($incidencia['fotografia'])): ?>
                        <img src="<?= base_url('uploads/jugadores/' . $incidencia['fotografia']); ?>" alt="Foto del Jugador" class="img-thumbnail" width="50" height="50">
                    <?php else: ?>
                        Sin fotografía
                    <?php endif; ?>
                </td>
                <td><?= $incidencia['jugador_nombre']; ?> <?= $incidencia['jugador_apellidos']; ?></td>
                <td><?= $incidencia['descripcion']; ?></td>
                <td><?= ($incidencia['tipo_tarjeta'] == 'R') ? 'Roja' : 'Amarilla'; ?></td>
                <td><?= $incidencia['fecha_incidencia']; ?></td>
                <td><?= $incidencia['fecha_suspension'] ?: 'N/A'; ?></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<?= $this->endSection(); ?>

<?= $this->section('scriptPage'); ?>
<script>
    let incidencias = <?= json_encode($incidencias); ?>;
    const equipoSelect = document.getElementById('equipo_id');
    const jugadorSelect = document.getElementById('jugador_id');
    const tablaIncidencias = document.getElementById('tabla-incidencias');
    const equipoPdfInput = document.getElementById('equipo_pdf');
    const jugadorPdfInput = document.getElementById('jugador_pdf');

    // Evento para filtrar cuando se selecciona un equipo
    equipoSelect.addEventListener('change', function() {
        let equipoId = this.value;
        jugadorSelect.innerHTML = '<option value="">Todos los jugadores con incidencia</option>';
        jugadorSelect.disabled = !equipoId;
        let jugadoresFiltrados = incidencias.filter(incidencia => !equipoId || incidencia.equipo_id == equipoId);
        let jugadoresUnicos = [...new Map(jugadoresFiltrados.map(item => [item.jugador_id, item])).values()];
        jugadoresUnicos.forEach(jugador => {
            let option = document.createElement('option');
            option.value = jugador.jugador_id;
            option.text = `${jugador.jugador_nombre} ${jugador.jugador_apellidos}`;
            jugadorSelect.appendChild(option);
        });
        equipoPdfInput.value = equipoId;
        filtrarTabla();
    });

    // Evento para filtrar cuando se selecciona un jugador
    jugadorSelect.addEventListener('change', function() {
        filtrarTabla();
        jugadorPdfInput.value = this.value;
    });

    // Asignar los valores seleccionados a los inputs ocultos antes de enviar el formulario
    formPdf.addEventListener('submit', function() {
        equipoPdfInput.value = equipoSelect.value;
        jugadorPdfInput.value = jugadorSelect.value;
    });

    // Función para filtrar la tabla según el equipo y el jugador seleccionados
    function filtrarTabla() {
        let equipoId = equipoSelect.value;
        let jugadorId = jugadorSelect.value;

        let filas = tablaIncidencias.querySelectorAll('tr');

        filas.forEach(fila => {
            let filaEquipoId = fila.getAttribute('data-equipo');
            let filaJugadorId = fila.getAttribute('data-jugador');
            if ((!equipoId || filaEquipoId == equipoId) && (!jugadorId || filaJugadorId == jugadorId)) {
                fila.style.display = '';
            } else {
                fila.style.display = 'none';
            }
        });
    }
</script>
<?= $this->endSection(); ?>
