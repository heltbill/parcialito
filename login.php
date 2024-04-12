<?php include('includes/header.php') ?>
<?php
if (isset($_POST['username']) && !empty($_POST['username'])) {
    $username = $_POST['username'];
    $_SESSION['username'] = $username;
    //header('Location: http://localhost/parcialito/');
}
?>
<div class="container p-4">
    <div class="card card-body shadow-sm w-25 mx-auto my-auto">
        <form action="login.php" method="post" autocomplete="off" id="form_login">
            <h4>Iniciar sesión</h4>
            <div class="form-group">
                <div class="input-group mb-3">
                    <input type="text" id="user" class="form-control" name="username" placeholder="Usuario">
                </div>
                <div class="input-group mb-3">
                    <input type="password" id="pass" class="form-control" name="password" placeholder="Contraseña">
                </div>
                <div id="errors_login" class="input-group mb-3" style="display: none;"></div>
                <div class="input-group">
                    <input type="submit" name="login" value="Iniciar sesión" class="btn btn-success w-100">
                </div>
            </div>
        </form>
    </div>
</div>
<script>
    function validateUser(username) {
        const regex = /^[A-Z][a-z]{6}[0-9]$/;
        return regex.test(username);
    }
    var formLoginValido = {
        username: false,
        password: false
    }
    document.addEventListener('DOMContentLoaded', function() {
        const userInput = document.querySelector('#user');
        const passInput = document.querySelector('#pass');
        if (userInput) {
            userInput.oninput = function() {
                if (this.value.length > 8) {
                    this.value = this.value.slice(0, 8);
                }
                if (validateUser(this.value)) {
                    this.style.border = '1px solid green';
                    formLoginValido.username = true;
                } else {
                    this.style.border = '1px solid red';
                    formLoginValido.username = false;
                }
            }
            userInput.onkeypress = function() {}
            userInput.onblur = function() {
                if (formLoginValido.username) {
                    this.style.border = ''
                }
            }
        }
        if (passInput) {
            passInput.oninput = function() {
                if (this.value.length >= 8) {
                    this.style.border = '1px solid green';
                    formLoginValido.password = true;
                } else {
                    this.style.border = '1px solid red';
                    formLoginValido.password = false;
                }
            }
            passInput.onblur = function() {
                if (formLoginValido.password) {
                    this.style.border = ''
                }
            }
        }
    })
</script>
<?php include('includes/footer.php') ?>