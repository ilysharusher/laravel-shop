@if($message = flash()->getMessage())
    <div class="{{ $message->class() }} py-4">
        {{ $message->message() }}
    </div>
@endif
