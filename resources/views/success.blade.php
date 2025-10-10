<x-app-layout>
    <div class="flex items-center justify-center h-screen bg-green-200 dark:bg-green-500">
        <canvas id="confetti-canvas" class="fixed inset-0 pointer-events-none"></canvas>
        
        <div class="max-w-md w-full p-8 bg-zinc-100 dark:bg-zinc-800 rounded-2xl shadow-lg text-center">
            <div class="text-green-600 dark:text-green-400 text-6xl animate-bounce mb-4">
                ✅
            </div>
            <h1 class="text-2xl font-bold text-zinc-800 dark:text-zinc-100 mb-2">
                {{ __('profile.handle_success') }}
            </h1>
        </br>
        <a href="{{ route('subscription.index') }}" class="text-lg font-medium text-zinc-800 dark:text-zinc-100 hover:text-indigo-500 dark:hover:text-indigo-400">{{ __('profile.show_subscription') }}</a>
        </div>
    </div>

    <script>
        (function() {
            const canvas = document.getElementById('confetti-canvas');
            const ctx = canvas.getContext('2d');
            
            canvas.width = window.innerWidth;
            canvas.height = window.innerHeight;
            
            const confetti = [];
            const confettiCount = 150;
            const colors = ['#10b981', '#34d399', '#6ee7b7', '#fbbf24', '#f59e0b', '#ec4899'];
            
            class Confetto {
                constructor() {
                    this.x = Math.random() * canvas.width;
                    this.y = Math.random() * canvas.height - canvas.height;
                    this.w = Math.random() * 10 + 5;
                    this.h = Math.random() * 5 + 5;
                    this.color = colors[Math.floor(Math.random() * colors.length)];
                    this.rotation = Math.random() * 360;
                    this.rotationSpeed = Math.random() * 10 - 5;
                    this.velocityX = Math.random() * 2 - 1;
                    this.velocityY = Math.random() * 3 + 2;
                    this.gravity = 0.1;
                }
                
                update() {
                    this.velocityY += this.gravity;
                    this.x += this.velocityX;
                    this.y += this.velocityY;
                    this.rotation += this.rotationSpeed;
                }
                
                draw() {
                    ctx.save();
                    ctx.translate(this.x, this.y);
                    ctx.rotate(this.rotation * Math.PI / 180);
                    ctx.fillStyle = this.color;
                    ctx.fillRect(-this.w / 2, -this.h / 2, this.w, this.h);
                    ctx.restore();
                }
            }
            
            for (let i = 0; i < confettiCount; i++) {
                confetti.push(new Confetto());
            }
            
            function animate() {
                ctx.clearRect(0, 0, canvas.width, canvas.height);
                
                let stillAnimating = false;
                confetti.forEach(c => {
                    c.update();
                    c.draw();
                    if (c.y < canvas.height) {
                        stillAnimating = true;
                    }
                });
                
                if (stillAnimating) {
                    requestAnimationFrame(animate);
                }
            }
            
            animate();
        })();
    </script>
</x-app-layout>