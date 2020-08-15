<!DOCTYPE html>
<html lang="zxx">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">
    <meta name="description" content="">
    <meta name="author" content=""> 
    <title>Login Panel</title>  
    <script src="{{ asset('public/template') }}/assets/js/app.js"></script>
    <link rel="stylesheet" href="{{ asset('public/template') }}/assets/css/app.css">
    <style>
        .loader {
            position: fixed;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: #F5F8FA;
            z-index: 9998;
            text-align: center;
        }
        
        .plane-container {
            position: absolute;
            top: 50%;
            left: 50%;
        }
    </style>
    
    <script>(function(w,d,u){w.readyQ=[];w.bindReadyQ=[];function p(x,y){if(x=="ready"){w.bindReadyQ.push(y);}else{w.readyQ.push(x);}};var a={ready:p,bind:p};w.$=w.jQuery=function(f){if(f===d||f===u){return a}else{p(f)}}})(window,document)</script>
</head>
<body class="light">
    <!-- Pre loader -->
    <div id="loader" class="loader">
        <div class="plane-container">
            <div class="preloader-wrapper small active">
                <div class="spinner-layer spinner-blue">
                    <div class="circle-clipper left">
                        <div class="circle"></div>
                    </div><div class="gap-patch">
                        <div class="circle"></div>
                    </div><div class="circle-clipper right">
                        <div class="circle"></div>
                    </div>
                </div>
                
                <div class="spinner-layer spinner-red">
                    <div class="circle-clipper left">
                        <div class="circle"></div>
                    </div><div class="gap-patch">
                        <div class="circle"></div>
                    </div><div class="circle-clipper right">
                        <div class="circle"></div>
                    </div>
                </div>
                
                <div class="spinner-layer spinner-yellow">
                    <div class="circle-clipper left">
                        <div class="circle"></div>
                    </div><div class="gap-patch">
                        <div class="circle"></div>
                    </div><div class="circle-clipper right">
                        <div class="circle"></div>
                    </div>
                </div>
                
                <div class="spinner-layer spinner-green">
                    <div class="circle-clipper left">
                        <div class="circle"></div>
                    </div><div class="gap-patch">
                        <div class="circle"></div>
                    </div><div class="circle-clipper right">
                        <div class="circle"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div id="app">
        <main>
            <div id="primary" class="blue4 p-t-b-100 height-full responsive-phone">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-6">
                            <img src="assets/img/icon/icon-plane.png" alt="">
                        </div>
                        <div class="col-lg-6 p-t-100">
                            <div class="text-white">
                                <h1>Selamat Datang </h1>
                                <p class="s-18 p-t-b-20 font-weight-lighter"></p>
                                </div>
                                <form action="" method="POST">
                                     @csrf() 
                                    <div class="row">
                                        <div class="col-lg-6">
                                            <div class="form-group has-icon"><i class="icon-envelope-o"></i>
                                                <input type="text" name="username" class="form-control form-control-lg no-b"
                                                placeholder="Email Address" value="admin">
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="form-group has-icon"><i class="icon-user-secret"></i>
                                                <input type="text" name="password" class="form-control form-control-lg no-b"
                                                placeholder="Password" value="123">
                                            </div>
                                        </div>
                                        <div class="col-lg-12">
                                            <input type="submit" class="btn btn-success btn-lg btn-block" value="Login Akses sistem">
                                            <p class="forget-pass text-white">Hanya dengan satu klik , Akses Console Username : admin , Password :  123 , <br />Silahkan login dengan mengklik tombol</p>
                                        </div> 
                                        
                                    </div>
                                </form>
                                @error('username')
                                <span class="alert alert-danger msg">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror

                                @error('password')
                                <span class="alert alert-danger msg">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                                

                            </div>
                        </div>
                    </div>
                </div>
                <!-- #primary -->
            </main>
            <!-- Right Sidebar -->
            
                <div class="control-sidebar-bg shadow white fixed"></div>
            </div>
            <!--/#app -->
            <script src="{{ asset('public/template') }}/assets/js/app.js"></script>
            
            
            
            
         </body>
        </html>