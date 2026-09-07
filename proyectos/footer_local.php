<?php
// /intranet/proyectos/footer_local.php
?>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

<script>
    $(document).ready(function() {
        var path = window.location.pathname;
        var page = path.split("/").pop();
        if (page == 'usuarios.php' || page == 'privilegios.php' || page == 'editar_privilegio.php') {
            $('#adminMenu').addClass('in');
        }
    });
</script>
</body>
</html>