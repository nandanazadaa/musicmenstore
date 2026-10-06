<!-- Member Registration Modal -->
<div id="memberModal" class="modal-overlay">
    <div class="modal-container">
        <button id="closeModal" class="modal-close">&times;</button>
        <h2 class="modal-title">Member Registration</h2>
        <div class="text-center py-4">
            <p class="text-white mb-6">Daftar sebagai anggota untuk mendapatkan akses ke profil Anda dan fitur eksklusif lainnya.</p>
            <a href="{{ route('member.register') }}"
                class="inline-block bg-transparent border border-[#4a4a4a] text-white px-8 py-3 rounded-lg hover:bg-[#2a2a2a] hover:border-[#6a6a6a] transition-all uppercase tracking-wider">
                Register Now
            </a>
            <p class="text-[#9a9a9a] text-sm mt-4">
                Already have an account?
                <a href="{{ route('member.login') }}"
                    class="text-white hover:text-[#6a6a6a] transition-colors">Login</a>
            </p>
        </div>
    </div>
</div>

