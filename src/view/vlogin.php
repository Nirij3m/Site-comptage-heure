<?php 
require_once "header.php";
?>
 <div id="containerLogin">
        <div class="vh-100" style="height: 100%;">
            <div class="container py-5 h-100">
              <div class="row d-flex justify-content-center align-items-center h-100">
                <div class="col col-xl-10">
                  <div class="card" style="border-radius: 1rem;">
                    <div class="row g-0">
                      <div class="col-md-6 col-lg-5 d-none d-md-block" style=" background: rgb(33,131,128);
                      background: radial-gradient(circle, rgba(33,131,128,1) 7%, rgba(169,204,213,1) 52%, rgba(180,199,222,1) 100%); ">
                        
                      </div>
                      <div class="col-md-6 col-lg-7 d-flex align-items-center">
                        <div class="card-body p-4 p-lg-5 text-black">
          
                          <form action="/login/result" method="POST">
          
                            <div class="d-flex align-items-center mb-3 pb-1">
                              <i class="fas fa-cubes fa-2x me-3" style="color: #ff6219;"></i>
                              <span class="h1 fw-bold mb-0">BDE</span>
                            </div>
          
                            <h5 class="fw-normal mb-3 pb-3" style="letter-spacing: 1px;">Connectez-vous à votre compte</h5>
          
                            <div class="form-outline mb-4">
                              <input type="email" id="form2Example17" name="mail" class="form-control form-control-lg" />
                              <label class="form-label" for="form2Example17">Adresse mail</label>
                            </div>
          
                            <div class="form-outline mb-4">
                              <input type="password" id="form2Example27" name="password" class="form-control form-control-lg" />
                              <label class="form-label" for="form2Example27">Mot de passe</label>
                            </div>
          
                            <div class="pt-1 mb-4">
                              <button class="btn btn-dark btn-lg btn-block" type="submit">Connexion</button>
                            </div>

                            <p class="mb-5 pb-lg-2" style="color: #393f81;">Problème de connexion ? Contactez Nirina sur discord</p>
                          </form>
          
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>