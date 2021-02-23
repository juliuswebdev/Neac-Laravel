<style>
  h1 { margin-top: 2 0px!important; }
  footer.main-footer,
  nav, aside, .breadcrumb { display: none!important }
  body.sidebar-mini .wrapper .content-wrapper { margin-left: 0!important }
</style>
@extends('layouts.dashboard')
@section('name_content')
    {{ $template->module }} - {{ $template->description }}
@endsection
@section('content')
<link rel="preconnect" href="https://fonts.gstatic.com">
<link href="https://fonts.googleapis.com/css2?family=Roboto:wght@100;300;400;700&display=swap" rel="stylesheet">
<style>
    body { font-family: 'Roboto', 'Helvetica', sans-serif;  }
    body * {line-height: 1.4; font-size: 13px; }
    td { font-size: inherit!important }
</style>

  <!-- Main content -->

  <section class="content">
    <div class="row">
        <table align="center" cellpadding="0" cellspacing="0" width="900" style="border-collapse: collapse; background-color: #f7f7f7;">
            <tr>
                <td style="padding: 20px;">
                    <table style="background-color: #fff;">
                        @if($template->header)
                        <thead>
                            <tr>
                                <td>
                                    {!! $template->header !!}
                                </td>
                            </tr>
                        </thead>
                        @endif
                        @if($template->body)
                        <tbody>
                            <tr>
                                <td style="padding: 2em 20px;">
                                    {!! $template->body !!}
                                </td>
                            </tr>
                        </tbody>
                        @endif
                        @if($template->footer)
                        <tfoot>
                            <tr>
                                <td>
                                    {!! $template->footer !!}
                                </td>
                            </tr>
                        </tfoot>
                        @endif
                    </table>
                </td>
            </tr>
        </table>
    </div>
  </section>
  <!-- /.content -->

@endsection