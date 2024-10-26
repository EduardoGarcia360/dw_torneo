<?= $this->extend('layout'); ?>

<?= $this->section('contenido'); ?>
<h3 class="my-3" id="titulo">Lista de Equipos</h3>

<a href="<?= base_url('equipos/new'); ?>" class="btn btn-success mb-3">Agregar Equipo</a>

<table class="table table-hover table-bordered my-3" aria-describedby="titulo">
    <thead class="table-dark">
        <tr>
            <th scope="col">ID</th>
            <th scope="col">Nombre del Equipo</th>
            <th scope="col">Opciones</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($equipos as $equipo) : ?>
            <tr>
                <td><?= $equipo['id']; ?></td>
                <td><?= $equipo['nombre_equipo']; ?></td>
                <td>
                    <a href="<?= base_url('equipos/'.$equipo['id'].'/edit'); ?>" class="btn btn-warning btn-sm me-2">Editar</a>
                    <button type="button" class="btn btn-danger btn-sm" data-toggle="modal"
                            data-target="#eliminaModal" 
                            data-url="<?= base_url('equipos/'.$equipo['id']); ?>"
                            onclick="setDeleteUrl('<?= base_url('equipos/'.$equipo['id']); ?>')"
                            >Eliminar</button>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<!-- Modal para confirmar la eliminación -->
<div class="modal fade" id="eliminaModal" tabindex="-1" role="dialog" aria-labelledby="eliminaModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="eliminaModalLabel">Confirmación</h1>
            </div>
            <div class="modal-body">
                <p>¿Deseas eliminar este equipo?</p>
            </div>
            <div class="modal-footer">
                <form id="form-elimina" action="" method="post">
                    <input type="hidden" name="_method" value="DELETE">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-danger">Eliminar</button>
                </form>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection(); ?>

<?= $this->section('scriptPage'); ?>
<script>
    // Función para establecer la URL de eliminación en el formulario del modal
    function setDeleteUrl(url) {
        document.getElementById('form-elimina').action = url;
    }

    document.addEventListener("DOMContentLoaded", function() {
        // Seleccionamos todos los botones de eliminar
        var deleteButtons = document.querySelectorAll('button[data-url]');

        // Iteramos sobre los botones para asignar el evento click
        deleteButtons.forEach(function(button) {
            button.addEventListener('click', function() {
                var url = button.getAttribute('data-url');
                setDeleteUrl(url);
            });
        });
    });
</script>
<?= $this->endSection(); ?>
