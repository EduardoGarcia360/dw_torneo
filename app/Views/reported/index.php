<?= $this->extend('layout'); ?>

<?= $this->section('contenido'); ?>
<h3 class="my-3">Consulta de Información de Jugador</h3>

<!-- Combo para seleccionar el jugador -->
<div class="form-group mb-3">
    <label for="jugador_id">Seleccionar Jugador</label>
    <select class="form-control" id="jugador_id" name="jugador_id">
        <option value="">Seleccione un jugador</option>
        <?php foreach ($jugadores as $jugador): ?>
            <option value="<?= $jugador['id']; ?>">
                <?= $jugador['nombres'] . ' ' . $jugador['apellidos']; ?>
            </option>
        <?php endforeach; ?>
    </select>
</div>

<!-- Botón para generar el PDF -->
<form method="get" action="<?= base_url('reported/generar_pdf'); ?>" target="_blank">
    <input type="hidden" id="jugador_pdf" name="jugador_id">
    <button type="submit" class="btn btn-primary">Generar PDF</button>
</form>

<!-- Información del jugador seleccionado -->
<div id="info-jugador">
    <h4>Datos del Jugador</h4>
    <p><strong>Nombre:</strong> <span id="nombre_jugador"></span></p>
    <p><strong>Fecha de Nacimiento:</strong> <span id="fecha_nacimiento"></span></p>
    <p><strong>Equipo:</strong> <span id="nombre_equipo"></span></p>
    <p><strong>Fotografía:</strong></p>
    <div id="foto_jugador"></div>

    <h4>Incidencias</h4>
    <ul id="lista_incidencias"></ul>

    <h4>Goles</h4>
    <ul id="lista_goles"></ul>
</div>

<script>
    let jugadores = <?= json_encode($jugadores); ?>;

    document.getElementById('jugador_id').addEventListener('change', function() {
        let jugadorId = this.value;
        let jugadorSeleccionado = jugadores.find(jugador => jugador.id == jugadorId);

        if (jugadorSeleccionado) {
            console.log('_', jugadorSeleccionado)
            document.getElementById('nombre_jugador').textContent = jugadorSeleccionado.nombres + ' ' + jugadorSeleccionado.apellidos;
            document.getElementById('fecha_nacimiento').textContent = jugadorSeleccionado.fecha_nacimiento;
            document.getElementById('nombre_equipo').textContent = jugadorSeleccionado.nombre_equipo;
            if (jugadorSeleccionado.fotografia) {
                document.getElementById('foto_jugador').innerHTML = `<img src="<?= base_url('uploads/jugadores/'); ?>/${jugadorSeleccionado.fotografia}" alt="Foto del Jugador" class="img-thumbnail" width="100" height="100">`;
            } else {
                document.getElementById('foto_jugador').textContent = 'Sin fotografía disponible';
            }
            let listaIncidencias = document.getElementById('lista_incidencias');
            listaIncidencias.innerHTML = '';
            console.log('solo incidencias', jugadorSeleccionado.incidencias)
            if (jugadorSeleccionado.incidencias) {
                jugadorSeleccionado.incidencias.forEach(incidencia => {
                    let li = document.createElement('li');
                    li.textContent = `${incidencia.descripcion} - Tarjeta: ${incidencia.tipo_tarjeta == 'R' ? 'Roja' : 'Amarilla'} - Fecha: ${incidencia.fecha_incidencia}`;
                    listaIncidencias.appendChild(li);
                });
            }
            let listaGoles = document.getElementById('lista_goles');
            listaGoles.innerHTML = '';
            console.log('solo goles', jugadorSeleccionado.goles)
            if (jugadorSeleccionado.goles) {
                jugadorSeleccionado.goles.forEach(gol => {
                    let li = document.createElement('li');
                    li.textContent = `${gol.cantidad_goles} goles en la jornada ${gol.numero_jornada} - Fecha: ${gol.fecha_jornada}`;
                    listaGoles.appendChild(li);
                });
            }
            
        }
    });

    // Asignar el valor del jugador seleccionado al input oculto para el PDF
    document.getElementById('jugador_id').addEventListener('change', function() {
        document.getElementById('jugador_pdf').value = this.value;
    });
</script>

<?= $this->endSection(); ?>
