<div>
    <!-- Do what you can, with what you have, where you are. - Theodore Roosevelt -->
    <div>
    <form id="resend-verification-form"
          action="{{ route('verification.resend') }}"
          method="POST"
          style="display: none;">
        @csrf
    </form>

    <a href="#"
       onclick="event.preventDefault(); document.getElementById('resend-verification-form').submit();"
       class="text-blue-600 hover:text-blue-800">
       {{ $slot }}
    </a>
</div>
</div>
