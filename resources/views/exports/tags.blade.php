<table>
    <thead>
    <tr>
        <th>Name</th>
        <th>Used</th>
    </tr>
    </thead>
    <tbody>
    @foreach($tags as $tag)
        <tr>
            <td width="auto">{{ $tag->name }}</td>
            <td width="auto">{{ $tag->posts->count() }}</td>
        </tr>
    @endforeach
    </tbody>
</table>