<body class="bg-primary bg-gradient">

<div class="container py-4"> 


    <!-- HEADER -->
    <div class="text-center text-white mb-4"> 
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
        <h1 class="fw-bold">Espace Opérateur</h1>
        <p class="mb-2">Simulateur Mobile Money</p>
        <a href="<?= site_url('client/login') ?>" class="btn btn-light btn-sm rounded-pill">
            Espace Client
        </a>
    </div>

    <!-- FLASH -->
    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success text-center">
            <?= session()->getFlashdata('success') ?>
        </div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-danger text-center">
            <?= session()->getFlashdata('error') ?>
        </div>
    <?php endif; ?>

    <!-- SECTION PREFIX -->
    <div class="card shadow mb-4">
        <div class="card-body">
            <h5 class="text-primary fw-bold">Configuration des préfixes</h5>

            <table class="table table-striped">
                <thead class="table-primary">
                    <tr>
                        <th>ID</th>
                        <th>Préfixe</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($prefixes)): ?>
                        <tr><td colspan="3">Aucun préfixe</td></tr>
                    <?php else: ?>
                        <?php foreach ($prefixes as $p): ?>
                            <tr>
                                <td><?= $p['id'] ?></td>
                                <td><?= esc($p['prefixe']) ?></td>
                                <td>
                                    <a class="btn btn-sm btn-danger"
                                       href="<?= site_url('operator/prefix/delete/' . $p['id']) ?>">
                                       Supprimer
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>

            <form action="<?= site_url('operator/prefix/add') ?>" method="post" class="row g-2">
                <?= csrf_field() ?>
                <div class="col">
                    <input type="text" name="prefixe" class="form-control" placeholder="033" required>
                </div>
                <div class="col-auto">
                    <button class="btn btn-primary">Ajouter</button>
                </div>
            </form>
        </div>
    </div>

    <!-- SECTION BAREME -->
    <div class="card shadow mb-4">
        <div class="card-body">
            <h5 class="text-primary fw-bold">Barèmes de frais</h5>

            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead class="table-primary">
                        <tr>
                            <th>Type</th>
                            <th>Min</th>
                            <th>Max</th>
                            <th>Fixe</th>
                            <th>%</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($baremes as $b): ?>
                            <tr>
                                <form action="<?= site_url('operator/bareme/edit') ?>" method="post">
                                    <?= csrf_field() ?>
                                    <input type="hidden" name="id" value="<?= $b['id'] ?>">
                                    <td><strong><?= esc($b['type_code']) ?></strong></td>
                                    <td><input type="number" name="montant_min" value="<?= $b['montant_min'] ?>" class="form-control"></td>
                                    <td><input type="number" name="montant_max" value="<?= $b['montant_max'] ?>" class="form-control"></td>
                                    <td><input type="number" name="frais_fixe" value="<?= $b['frais_fixe'] ?>" class="form-control"></td>
                                    <td><input type="number" name="frais_pourcentage" value="<?= $b['frais_pourcentage'] ?>" class="form-control"></td>
                                    <td>
                                        <button class="btn btn-success btn-sm">✔</button>
                                        <a class="btn btn-danger btn-sm"
                                           href="<?= site_url('operator/bareme/delete/' . $b['id']) ?>">
                                           ✖
                                        </a>
                                    </td>
                                </form>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <!-- ADD -->
            <form action="<?= site_url('operator/bareme/add') ?>" method="post" class="row g-2">
                <?= csrf_field() ?>
                <div class="col-md-2">
                    <select name="id_type_operation" class="form-select">
                        <?php foreach ($typeOperations as $type): ?>
                            <option value="<?= $type['id'] ?>"><?= esc($type['code']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col"><input type="number" name="montant_min" class="form-control" placeholder="Min"></div>
                <div class="col"><input type="number" name="montant_max" class="form-control" placeholder="Max"></div>
                <div class="col"><input type="number" name="frais_fixe" class="form-control" placeholder="Fixe"></div>
                <div class="col"><input type="number" name="frais_pourcentage" class="form-control" placeholder="%"></div>
                <div class="col-auto">
                    <button class="btn btn-primary">Ajouter</button>
                </div>
            </form>
        </div>
    </div>

    <!-- GAINS -->
    <div class="card shadow mb-4">
        <div class="card-body text-center">
            <h5 class="text-primary fw-bold">Gains</h5>
            <p>Retraits: <strong><?= number_format($gains['RET']['total']) ?> Ar</strong></p>
            <p>Transferts: <strong><?= number_format($gains['TRA']['total']) ?> Ar</strong></p>
            <p>Total: <strong><?= number_format($gains['RET']['total'] + $gains['TRA']['total']) ?> Ar</strong></p>
        </div>
    </div>

    <!-- CLIENTS -->
    <div class="card shadow">
        <div class="card-body">
            <h5 class="text-primary fw-bold">Clients</h5>

            <table class="table table-hover">
                <thead class="table-primary">
                    <tr>
                        <th>ID</th>
                        <th>Téléphone</th>
                        <th>Solde</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($clients as $c): ?>
                        <tr>
                            <td><?= $c['id'] ?></td>
                            <td><strong><?= esc($c['telephone']) ?></strong></td>
                            <td><?= number_format($c['solde']) ?> Ar</td>
                            <td><?= $c['date_creation'] ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

        </div>
    </div>

</div>
</body>