<div class="container col-xl-10 col-xxl-8 px-4 py-5">
    <?php if (isset($model["error"])) : ?>
        <div class="row">
            <div class="alert alert-danger" role="alert">
                <?= $model["error"]  ?>
            </div>
        </div>
    <?php endif ?>
    <div class="row align-items-center g-lg-5 py-5">
        <div class="col-lg-7 text-center text-lg-start">
            <h1 class="display-4 fw-bold lh-1 mb-3"><?= $model["title"] ?></h1>
            <p class="col-lg-10 fs-4">by <a target="_blank" href="https://github.com/RizalFIrdaus">Muhammad Rizal Firdaus</a></p>

        </div>
        <div class="col-md-10 mx-auto col-lg-5">
            <form class="p-4 p-md-5 border rounded-3 bg-light" method="post" action="/users/password">
                <input type="hidden" name="id" value="<?= $model["user"]["id"] ?>">
                <div class="form-floating mb-3">
                    <input name="oldPassword" type="password" class="form-control" id="oldPassword" placeholder="old password">
                    <label for="oldPassword">Old Password</label>
                </div>
                <div class="form-floating mb-3">
                    <input name="newPassword" type="password" class="form-control" id="newPassword" placeholder="password">
                    <label for="newPassword">New Password</label>
                </div>
                <button class="w-100 btn btn-lg btn-primary" type="submit">Change Password</button>
                <a class="w-100 my-2 btn btn-lg btn-danger" href="/">Back</a>
            </form>
        </div>
    </div>
</div>