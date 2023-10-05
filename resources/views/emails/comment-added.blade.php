<x-mail::message>
# A comment was posted on your idea

{{$comment->user->name}} on your idea.

**{{$comment->idea->title}}**

Comment : {{$comment->body}}

<x-mail::button :url="route('idea.show', $comment->idea)">
    Go To Idea
</x-mail::button>

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>