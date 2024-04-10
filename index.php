<?php require('db.php'); ?>
<?php include('includes/header.php') ?>

<!-- CODE INDEX -->
<?php if (isset($_SESSION['username'])) : ?>
    <div class="container-fluid p-4">
        <div class="row">
            <div class="col-md-4">
                <div class="card card-body shadow-sm">
                    <form action="save.php" method="post" autocomplete="off" id="form_add">
                        <div class="form-group">
                            <div class="input-group mb-3">
                                <input type="number" min="0" class="form-control" name="nit" placeholder="NIT">
                            </div>
                            <div class="input-group mb-3">
                                <div class="row mx-auto">
                                    <div class="col"><input type="text" class="form-control" name="first_name" placeholder="Primer nombre"></div>
                                    <div class="col"><input type="text" class="form-control" name="middle_name" placeholder="Segundo nombre"></div>
                                </div>
                            </div>
                            <div class="input-group mb-3">
                                <div class="row mx-auto">
                                    <div class="col"><input type="text" class="form-control" name="first_last_name" placeholder="Primer apellido"></div>
                                    <div class="col"><input type="text" class="form-control" name="middle_last_name" placeholder="Segundo apellido"></div>
                                </div>
                            </div>
                            <div class="input-group mb-3">
                                <input class="form-control" type="date" id="datepicker" name="birth_date" max="<?php echo date('Y-m-d', strtotime('yesterday')) ?>" placeholder="Fecha de nacimiento" required />
                            </div>
                            <div class="input-group mb-3">
                                <input type="number" min="0" class="form-control" name="phone" placeholder="Teléfono">
                            </div>
                            <div class="input-group mb-3">
                                <input type="email" class="form-control" name="email" placeholder="Correo electronico">
                            </div>
                            <div class="input-group" id="actions">
                                <input type="submit" name="save" value="Guardar" class="btn btn-success w-100">
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="col-md-8">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover table-sm align-middle shadow-sm">
                        <thead class="text-center">
                            <tr>
                                <th style="width: 103px;" class="bg-light fw-semibold">NIT</th>
                                <th style="width: 285px;" class="bg-light fw-semibold">Nombre completo</th>
                                <th style="width: 175px;" class="bg-light fw-semibold">Fecha de nacimiento</th>
                                <th style="width: 103px;" class="bg-light fw-semibold">Teléfono</th>
                                <th style="width: 264px;" class="bg-light fw-semibold">Correo electronico</th>
                                <th style="width: 84px;" class="bg-light fw-semibold">Acciones</th>
                            </tr>
                        </thead>
                        <tbody id="users"></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
<?php else : ?>
    <?php header('Location: login.php') ?>
<?php endif ?>

<script>
    function calculateAge(date) {
        var today = new Date();
        var birth = new Date(date);
        var age = today.getFullYear() - birth.getFullYear();
        var m = today.getMonth() - birth.getMonth();

        if (m < 0 || (m === 0 && today.getDate() < birth.getDate())) {
            age--;
        }

        return age;
    }

    function getDifferentValues(obj, targetValue) {
        return Object.entries(obj)
            .filter(([key, value]) => value !== targetValue)
            .reduce((acc, [key, value]) => {
                acc[key] = value;
                return acc;
            }, {});
    }

    function areAllValuesEqualTo(obj, targetValue) {
        return Object.values(obj).every(value => value === targetValue);
    }

    var formValido = {
        nit: false,
        first_name: false,
        first_last_name: false,
        middle_last_name: false,
        birth_date: false,
        phone: false,
        email: false
    };

    document.addEventListener('DOMContentLoaded', function() {
        const numberInputs = document.querySelectorAll('input[type="number"]');
        const textInputs = document.querySelectorAll('input[type="text"]');
        const emailInputs = document.querySelectorAll('input[type="email"]');
        let birthDate = document.getElementById('datepicker')
        if (birthDate) {
            birthDate.addEventListener('change', (e) => {
                let selectedDate = e.target.value
                calculateAge(selectedDate) >= 18 ? formValido[e.target.name] = true : formValido[e.target.name] = false;
            })
        }
        numberInputs.forEach(i => i.oninput = function() {
            if (this.value.length > 10) {
                this.value = this.value.slice(0, 10);
            }
        })
        numberInputs.forEach(i => i.onblur = function() {
            this.value.length !== 0 ? formValido[i.name] = true : formValido[i.name] = false;
        })

        textInputs.forEach(i => i.onkeypress = function(e) {
            const charCode = e.charCode;
            const isValidChar = (charCode >= 65 && charCode <= 90) || (charCode >= 97 && charCode <= 122);
            if (!isValidChar) {
                e.preventDefault();
            }
        })
        textInputs.forEach(i => i.oninput = function() {
            if (this.value.length > 20) {
                this.value = this.value.slice(0, 20);
            }
        })
        textInputs.forEach(i => i.onblur = function() {
            if (i.name !== 'middle_name') {
                this.value.length !== 0 ? formValido[i.name] = true : formValido[i.name] = false;
            }
        })

        emailInputs.forEach(i => i.onblur = function() {
            const re = /^(([^<>()[\]\.,;:\s@\"]+(\.[^<>()[\]\.,;:\s@\"]+)*)|(\".+\"))@(([^<>()[\]\.,;:\s@\"]+\.)+[^<>()[\]\.,;:\s@\"]{2,})$/;
            var eValue = this.value;
            const ok = re.exec(eValue);
            ok ? formValido[i.name] = true : formValido[i.name] = false;
        })

    });
</script>
<!-- END INDEX -->
<?php include('includes/footer.php') ?>