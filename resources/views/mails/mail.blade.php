@if( isset($header) || isset($footer))
<table align="center" cellpadding="0" cellspacing="0" width="900" style="border-collapse: collapse; background-color: #f7f7f7;">
    <tr>
        <td style="padding: 20px;">
            <table style="background-color: #fff;">
                @if(isset($header))
                    <thead>
                        <tr>
                            <td>{!! $header !!}</td>
                        </tr>
                    </thead>
                @endif
                <tbody>
                    <tr>
                        <td style="padding: 2em 20px;">{!! $body !!}</td>
                    </tr>
                </tbody>
                @if(isset($footer))
                <tfoot>
                    <tr>
                        <td>{!! $footer !!}</td>
                    </tr>
                </tfoot>
                @endif
            </table>
        </td>
    </tr>
</table>
@else
{!! $body !!}
@endif