<table id="mail-table"  class="table table-striped">
    <thead class="thead-dark">
        <tr>
            <th style="width: 10px;"></th>
            <th style="width: 350px;">Subject</th>
            <th>Message</th>
            <th style="width: 150px;">Date Created</th>
            <th>From</th>
            <th style="width: 150px;">Attachments</th>
        </tr>
    </thead>
    <tbody>
        @php $count = 1; @endphp
        @foreach($mails as $mail)
        <tr data_id="{{ $mail->id }}">
            <td>{{ $count++ }}</td>
            <td><a onclick="open_window('{{ route('mail.view', $mail->id) }}')" href="javascript:void(0)"><span style="width:350px; display: block;">{{ $mail->subject }}</span></a></td>
            <td><div style="max-height: 20px; overflow: hidden;">{!! $mail->messages !!}</div></td>
            <td>{{ $mail->created_at }}</td>
            <td>{{ $mail->user->first_name }} {{ $mail->user->last_name }}</td>
            <td>
                @if($mail->attachments)
                    @php
                        $attachments = explode(',',$mail->attachments);
                    @endphp
                    @foreach($attachments as $attachment)
                        @php
                            $item = explode("_nurse_", $attachment);
                            $ext = explode(".", $attachment);
                        @endphp
                        <a href="{{ $document_path }}{{ $attachment }}" download>{{ $item[0] }}.{{ $ext[1] }}</a><br>
                    @endforeach
                @endif
            </td>
        </tr>
        @endforeach
    </tbody>
</table>