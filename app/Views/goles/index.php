<?= $this->extend('layout'); ?>

<?= $this->section('contenido'); ?>
<h3 class="my-3" id="titulo">Lista de Goles</h3>

<a href="<?= base_url('goles/new'); ?>" class="btn btn-success mb-3">Agregar Gol</a>

<table class="table table-hover table-bordered my-3" aria-describedby="titulo">
    <thead class="table-dark">
        <tr>
            <th scope="col">ID</th>
            <th scope="col">Jugador</th>
            <th scope="col">Jornada</th>
            <th scope="col">Cantidad de Goles</th>
            <th scope="col">Opciones</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($goles as $gol) : ?>
            <tr>
                <td><?= $gol['id']; ?></td>
                <td><?= $gol['nombres'] . ' ' . $gol['apellidos']; ?></td>
                <td>Jornada <?= $gol['numero_jornada']; ?></td>
                <td><?= $gol['cantidad_goles']; ?></td>
                <td>
                    <a href="<?= base_url('goles/'.$gol['id'].'/edit'); ?>" class="btn btn-warning btn-sm me-2">Editar</a>
                    <button type="button" class="btn btn-danger btn-sm" data-toggle="modal"
                            data-target="#eliminaModal" 
                            data-url="<?= base_url('goles/'.$gol['id']); ?>"
                            onclick="setDeleteUrl('<?= base_url('goles/'.$gol['id']); ?>')"
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
                <p>¿Deseas eliminar este registro de gol?</p>
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
        var deleteButtons = document.querySelectorAll('button[data-url]');
        deleteButtons.forEach(function(button) {
            button.addEventListener('click', function() {
                var url = button.getAttribute('data-url');
                setDeleteUrl(url);
            });
        });
    });
</script>
<?= $this->endSection(); ?>
