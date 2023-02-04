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
            <h1 class="display-4 fw-bold lh-1 mb-3">Profile</h1>
            <p class="col-lg-10 fs-4">Hello <?= $model["user"]["name"] ?></p>
        </div>
        <div class="col-md-10 mx-auto col-lg-5">
            <form class="p-4 p-md-5 border rounded-3 bg-light" method="post" action="/users/profile">
                <div class="form-floating mb-3">
                    <input name="name" type="text" class="form-control" id="name" placeholder="name" value="<?= $model["user"]["name"] ?>">
                    <label for="name">Name</label>
                </div>
                <button class="w-100 btn btn-lg btn-primary" type="submit">Update Profile</button>
                <a class="w-100 btn btn-lg btn-danger my-2" href="/">Back</a>
            </form>
        </div>
    </div>
</div>