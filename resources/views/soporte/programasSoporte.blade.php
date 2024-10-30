		@extends('layouts.app')
		@section('title', 'Soporte')
		@section('content')
            <div class="container">
                <div class="widget-list">
                    <div class="row justify-content-center">
                        <div class="col-md-6 m-3">
                            <a href="https://www.teamviewer.com/es-mx/" target="_blank"><img src="{{asset('assets/img/teamviewer.png')}}"></a>
                            <div class="text-center fs-16">
                                <a href="https://www.teamviewer.com/es-mx/" target="_blank">Team Viewer</a>
                            </div>
                        </div>
                        <div class="col-md-6 m-3">
                            <a href="https://showmypc.com/" target="_blank"><img src="{{asset('assets/img/showmypc.jpg')}}"></a>
                            <div class="text-center fs-16">
                                <a href="https://showmypc.com/" target="_blank">Show My PC</a>
                            </div>
                        </div>
                        <div class="col-md-6 m-3">
                            <a href="https://anydesk.es/escritorio-remoto" target="_blank"><img src="{{asset('assets/img/anydesk.jpg')}}"></a>
                            <div class="text-center fs-16">
                                <a href="https://anydesk.es/escritorio-remoto" target="_blank">Any Desk</a>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /.widget-list -->
                
                
            </div>
            <!-- /.container -->
	@stop			
			
	@section('scripts')
	@stop
