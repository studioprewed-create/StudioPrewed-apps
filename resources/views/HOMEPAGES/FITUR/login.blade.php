<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Studio Prewed | Login</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('public/asset/HOMEPAGE/FITUR/login.css') }}">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&family=Montserrat:wght@400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>
    <div class="bg-circle circle-1"></div>
    <div class="bg-circle circle-2"></div>
    <div class="bg-circle circle-3"></div>
    
    <div class="login-container">
        <div class="login-card">
            <div class="logo-header">
                <div class="logo-container">
                    <img src="{{ asset('asset/PICTURESET/LOGOSPLOGIN.png') }}" alt="Studio Prewed Logo" class="logo">
                    <div class="brand-name">Studio Prewed</div>
                </div>
                <div class="brand-tagline">Capture Forever</div>
            </div>
            
            <div class="login-header">
                <h2 class="login-title">Welcome Back</h2>
                <p class="login-subtitle">Sign in to access your creative studio</p>
            </div>
            
            <form action="{{ route('login.verify') }}" method="POST" class="login-form" id="loginForm">
                @csrf
                
                <div class="form-group">
                    <label class="input-label" for="email">Email Address</label>
                    <div class="input-container">
                        <input type="email" id="email" name="email" class="underline-input" placeholder="hello@studioprewed.com" required>
                        <div class="input-underline"></div>
                        <i class="far fa-envelope input-icon"></i>
                    </div>
                </div>
                
                <div class="form-group">
                    <label class="input-label" for="password">Password</label>
                    <div class="input-container">
                        <input type="password" id="password" name="password" class="underline-input" placeholder="••••••••" required>
                        <div class="input-underline"></div>
                        <i class="fas fa-lock input-icon"></i>
                        <button type="button" class="password-toggle" id="togglePassword">
                            <i class="far fa-eye"></i>
                        </button>
                    </div>
                </div>
                
                <button type="submit" class="btn-login">
                    <i class="fas fa-camera" style="margin-right: 10px;"></i> Enter Studio
                </button>
                
                <div class="divider">
                    <span>Continue With</span>
                </div>
                
                <div class="action-buttons">
                    <a href="{{ route('Registrasi') }}" class="btn-action">
                        <i class="fas fa-user-plus"></i> <span>Create Account</span>
                    </a>
                    <a href="{{ url('/') }}" class="btn-action">
                        <i class="fas fa-eye"></i> <span>Browse as Guest</span>
                    </a>
                </div>
            </form>
            
            <div class="login-footer">
                <a href="#" class="footer-link">Forgot your password?</a>
                <span style="color: var(--gray); margin: 0 10px;">•</span>
                <a href="#" class="footer-link">Need help?</a>
            </div>
        </div>
    </div>

    <script>
        // Toggle password visibility with animation
        const toggleBtn = document.getElementById('togglePassword');
        const eyeIcon = toggleBtn.querySelector('i');
        
        toggleBtn.addEventListener('click', function() {
            const passwordInput = document.getElementById('password');
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                eyeIcon.classList.remove('fa-eye');
                eyeIcon.classList.add('fa-eye-slash');
                toggleBtn.style.color = 'var(--primary-light)';
            } else {
                passwordInput.type = 'password';
                eyeIcon.classList.remove('fa-eye-slash');
                eyeIcon.classList.add('fa-eye');
                toggleBtn.style.color = '';
            }
        });
        
        // Form submission with cool animation
        document.getElementById('loginForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const submitBtn = this.querySelector('.btn-login');
            const originalContent = submitBtn.innerHTML;
            
            // Animate button
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Accessing Studio...';
            submitBtn.style.background = 'linear-gradient(135deg, var(--secondary) 0%, var(--accent) 100%)';
            
            // Simulate loading with particles effect
            createParticles(submitBtn);
            
            // Simulate API call
            setTimeout(() => {
                submitBtn.innerHTML = '<i class="fas fa-check"></i> Welcome!';
                submitBtn.style.background = 'linear-gradient(135deg, #8B6B4E 0%, #A67C52 100%)';
                
                // Reset after delay
                setTimeout(() => {
                    submitBtn.innerHTML = originalContent;
                    submitBtn.style.background = 'linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%)';
                    
                    // Actually submit the form after animation
                    this.submit();
                }, 1000);
            }, 2000);
        });
        
        // Create particle effect
        function createParticles(element) {
            const rect = element.getBoundingClientRect();
            
            for (let i = 0; i < 8; i++) {
                const particle = document.createElement('div');
                particle.style.position = 'fixed';
                particle.style.width = '6px';
                particle.style.height = '6px';
                particle.style.background = 'var(--primary-light)';
                particle.style.borderRadius = '50%';
                particle.style.pointerEvents = 'none';
                particle.style.zIndex = '1000';
                
                // Position at button center
                particle.style.left = rect.left + rect.width / 2 + 'px';
                particle.style.top = rect.top + rect.height / 2 + 'px';
                
                document.body.appendChild(particle);
                
                // Animate particle
                const angle = Math.random() * Math.PI * 2;
                const distance = 50 + Math.random() * 50;
                const duration = 800 + Math.random() * 400;
                
                particle.animate([
                    { 
                        transform: 'translate(0, 0) scale(1)',
                        opacity: 1
                    },
                    { 
                        transform: translate(${Math.cos(angle) * distance}px, ${Math.sin(angle) * distance}px) scale(0),
                        opacity: 0
                    }
                ], {
                    duration: duration,
                    easing: 'ease-out'
                });
                
                // Remove particle after animation
                setTimeout(() => {
                    particle.remove();
                }, duration);
            }
        }
        
        // Add floating effect to input labels on focus
        const inputs = document.querySelectorAll('.underline-input');
        inputs.forEach(input => {
            const label = input.parentElement.parentElement.querySelector('.input-label');
            
            input.addEventListener('focus', function() {
                label.style.color = 'var(--primary-light)';
                label.style.transform = 'translateY(-5px)';
            });
            
            input.addEventListener('blur', function() {
                if (!this.value) {
                    label.style.color = '';
                    label.style.transform = '';
                }
            });
        });
        
        // Add ripple effect to action buttons
        const actionBtns = document.querySelectorAll('.btn-action');
        actionBtns.forEach(btn => {
            btn.addEventListener('click', function(e) {
                const x = e.clientX - e.target.getBoundingClientRect().left;
                const y = e.clientY - e.target.getBoundingClientRect().top;
                
                const ripple = document.createElement('span');
                ripple.style.position = 'absolute';
                ripple.style.background = 'rgba(166, 124, 82, 0.3)';
                ripple.style.borderRadius = '50%';
                ripple.style.transform = 'scale(0)';
                ripple.style.animation = 'ripple 0.6s linear';
                ripple.style.left = x + 'px';
                ripple.style.top = y + 'px';
                ripple.style.width = '100px';
                ripple.style.height = '100px';
                ripple.style.marginLeft = '-50px';
                ripple.style.marginTop = '-50px';
                
                this.appendChild(ripple);
                
                setTimeout(() => {
                    ripple.remove();
                }, 600);
            });
        });
        
        // Add ripple animation
        const style = document.createElement('style');
        style.textContent = `
            @keyframes ripple {
                to {
                    transform: scale(4);
                    opacity: 0;
                }
            }
        `;
        document.head.appendChild(style);
    </script>
</body>
</html>