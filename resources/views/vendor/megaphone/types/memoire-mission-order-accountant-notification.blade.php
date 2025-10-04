<x-megaphone::notification.notification>
    <x-slot:icon>
        {{-- <x-megaphone::icons.approve /> --}}
        <img src="{{Vite::asset('resources/images/'.$announcement['icon'].'.png')}}" alt="">
    </x-slot:icon>
<x-slot:body>
    {!!nl2br(e(str_replace("\\n", "\n", $announcement['body'])))!!}
</x-slot:body>
    <x-slot:title>
        <x-megaphone::notification.title :link="$announcement['link']">
            {{ $announcement['title'] }}
        </x-megaphone::notification.title>
    </x-slot:title>

    <x-slot:date>
        <x-megaphone::notification.date :createdAt="$created_at" />
    </x-slot:date>

    <x-slot:link>
        <x-megaphone::notification.link
            :link="$announcement['link']"
            :newWindow="$announcement['linkNewWindow']"
            :linkText="$announcement['linkText']"
        />
    </x-slot:link>
</x-megaphone::notification.notification>
