<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Store Inventory System</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        .login-container {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
            padding: 2.5rem;
            width: 100%;
            max-width: 450px;
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
        
        .store-logo {
            text-align: center;
            margin-bottom: 2rem;
        }
        
        .store-logo i {
            font-size: 4rem;
            background: linear-gradient(135deg, #667eea, #764ba2);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        
        .store-name {
            color: #333;
            font-size: 1.8rem;
            font-weight: 700;
            margin-top: 0.5rem;
        }
        
        .form-floating {
            margin-bottom: 1rem;
        }
        
        .form-control {
            border: 2px solid #e1e5e9;
            border-radius: 12px;
            padding: 1rem;
            transition: all 0.3s ease;
            background: rgba(255, 255, 255, 0.8);
        }
        
        .form-control:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
            background: white;
        }
        
        .btn-login {
            background: linear-gradient(135deg, #667eea, #764ba2);
            border: none;
            padding: 1rem;
            border-radius: 12px;
            font-weight: 600;
            letter-spacing: 0.5px;
            transition: all 0.3s ease;
            text-transform: uppercase;
            font-size: 0.9rem;
        }
        
        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(102, 126, 234, 0.3);
        }
        
        .role-selector {
            margin-bottom: 1.5rem;
        }
        
        .role-option {
            background: white;
            border: 2px solid #e1e5e9;
            border-radius: 12px;
            padding: 1rem;
            margin-bottom: 0.5rem;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
        }
        
        .role-option:hover {
            border-color: #667eea;
            background: rgba(102, 126, 234, 0.05);
        }
        
        .role-option.selected {
            border-color: #667eea;
            background: rgba(102, 126, 234, 0.1);
        }
        
        .role-option input[type="radio"] {
            display: none;
        }
        
        .role-icon {
            font-size: 1.5rem;
            margin-right: 1rem;
            width: 30px;
            text-align: center;
        }
        
        .alert {
            border-radius: 12px;
            border: none;
            margin-bottom: 1.5rem;
        }
        
        .default-credentials {
            background: rgba(255, 255, 255, 0.1);
            border-radius: 12px;
            padding: 1rem;
            margin-top: 1.5rem;
            font-size: 0.85rem;
        }
        
        .credential-item {
            background: rgba(255, 255, 255, 0.2);
            border-radius: 8px;
            padding: 0.5rem;
            margin-bottom: 0.5rem;
        }
    </style>
</head>
<body>
        <div class="login-container">
            <!-- Store Logo/Branding -->
            <div class="store-logo">
                <i class="fas fa-store"></i>
                <h2 class="store-name">QuickMart Inventory</h2>
                <p class="text-muted">Convenience Store Management</p>
            </div>

            <!-- Error/Success Messages -->
            @if(isset($errors) && $errors->has('login'))
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    {{ $errors->first('login') }}
                </div>
            @endif

            @if(session('success'))
                <div class="alert alert-success">
                    <i class="fas fa-check-circle me-2"></i>
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    {{ session('error') }}
                </div>
            @endif

            <!-- Login Form -->
            <form method="POST" action="{{ route('login.post') }}" id="loginForm">
                @csrf
                
                <!-- Role Selection -->
                <div class="role-selector">
                    <label class="form-label fw-bold">Select Your Role</label>
                    <div class="role-option" onclick="selectRole('admin')">
                        <input type="radio" name="role" value="admin" id="role_admin" {{ old('role') == 'admin' ? 'checked' : '' }}>
                        <div class="role-icon">
                            <i class="fas fa-user-shield text-danger"></i>
                        </div>
                        <div>
                            <strong>Administrator</strong>
                            <br><small class="text-muted">Full system access</small>
                        </div>
                    </div>
                    
                    <div class="role-option" onclick="selectRole('staff')">
                        <input type="radio" name="role" value="staff" id="role_staff" {{ old('role') == 'staff' ? 'checked' : '' }}>
                        <div class="role-icon">
                            <i class="fas fa-user text-primary"></i>
                        </div>
                        <div>
                            <strong>Staff Member</strong>
                            <br><small class="text-muted">Sales & inventory access</small>
                        </div>
                    </div>
                </div>

                <!-- Username Field -->
                <div class="form-floating">
                    <input type="text" class="form-control" id="username" name="username" 
                           placeholder="Username" value="{{ old('username') }}" required>
                    <label for="username">
                        <i class="fas fa-user me-2"></i>Username
                    </label>
                </div>

                <!-- Password Field -->
                <div class="form-floating">
                    <input type="password" class="form-control" id="password" name="password" 
                           placeholder="Password" required>
                    <label for="password">
                        <i class="fas fa-lock me-2"></i>Password
                    </label>
                </div>

                <!-- Remember Me -->
                <div class="form-check mb-3">
                    <input class="form-check-input" type="checkbox" id="remember" name="remember">
                    <label class="form-check-label" for="remember">
                        Remember me for 30 days
                    </label>
                </div>

                <!-- Login Button -->
                <button type="submit" class="btn btn-primary btn-login w-100">
                    <i class="fas fa-sign-in-alt me-2"></i>
                    Login to System
                </button>
            </form>

        </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script>
        // Role selection functionality
        function selectRole(role) {
            // Remove selected class from all role options
            document.querySelectorAll('.role-option').forEach(option => {
                option.classList.remove('selected');
            });
            
            // Add selected class to clicked option
            event.currentTarget.classList.add('selected');
            
            // Check the radio button
            document.getElementById('role_' + role).checked = true;
        }

        // Auto-fill credentials when role is selected
        document.addEventListener('DOMContentLoaded', function() {
            const roleOptions = document.querySelectorAll('.role-option');
            const usernameField = document.getElementById('username');
            const passwordField = document.getElementById('password');
            
            roleOptions.forEach(option => {
                option.addEventListener('click', function() {
                    const role = this.querySelector('input').value;
                    if (role === 'admin') {
                        usernameField.value = 'admin';
                        passwordField.value = 'admin123';
                    } else if (role === 'staff') {
                        usernameField.value = 'staff';
                        passwordField.value = 'staff123';
                    }
                });
            });

            // Set default selection if old value exists
            const oldRole = document.querySelector('input[name="role"]:checked');
            if (oldRole) {
                oldRole.closest('.role-option').classList.add('selected');
            }
        });

        // Form validation
        document.getElementById('loginForm').addEventListener('submit', function(e) {
            const role = document.querySelector('input[name="role"]:checked');
            const username = document.getElementById('username').value;
            const password = document.getElementById('password').value;

            if (!role) {
                e.preventDefault();
                alert('Please select your role (Admin or Staff)');
                return;
            }

            if (!username.trim()) {
                e.preventDefault();
                alert('Please enter your username');
                document.getElementById('username').focus();
                return;
            }

            if (!password.trim()) {
                e.preventDefault();
                alert('Please enter your password');
                document.getElementById('password').focus();
                return;
            }
        });
    </script>
</body>
</html>

