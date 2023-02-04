<div class="container col-xl-10 col-xxl-8 px-4 py-5">
    <?php if (isset($model["error"])) : ?>
        <div class="row">
            <div class="alert alert-danger" role="alert">
                <?= $model["error"] ?>
            </div>
        </div>
    <?php endif ?>
    <div class="row align-items-center g-lg-5 py-5">
        <div class="col-lg-7 text-center text-lg-start">
            <h1 class="display-4 fw-bold lh-1 mb-3">Register</h1>
            <p class="col-lg-10 fs-4">by <a target="_blank" href="https://github.com/RizalFIrdaus">Muhammad Rizal Firdaus</a></p>
            <a class="btn btn-lg btn-secondary my-2" ~href="/">Home</a>

        </div>
        <div class="col-md-10 mx-auto col-lg-5">
            <form class="p-4 p-md-5 border rounded-3 bg-light" method="post" action="/users/register">
                <div class="form-floating mb-3">
                    <input name="id" type="text" class="form-control" id="id" placeholder="id">
                    <label for="id">Id</label>
                </div>
                <div class="form-floating mb-3">
                    <input name="name" type="text" class="form-control" id="name" placeholder="name">
                    <label for="name">Name</label>
                </div>
                <div class="form-floating mb-3">
                    <input name="password" type="password" class="form-control" id="password" placeholder="password">
                    <label for="password">Password</label>
                </div>
                <button class="w-100 btn btn-lg btn-primary" type="submit">Register</button>
                <a class="w-100 btn btn-lg btn-success my-2" href="/users/login">Login</a>

            </form>
        </div>
    </div>
</div>