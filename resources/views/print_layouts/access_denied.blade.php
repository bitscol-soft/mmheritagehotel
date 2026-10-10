
@extends('layouts.master')
@section('title','Dashboard')
@section('css')
    <link rel="stylesheet" href="/assets/css/bootstrap-datepicker3.min.css" />
    <link rel="stylesheet" href="/assets/css/bootstrap-timepicker.min.css" />
    <link rel="stylesheet" href="/assets/css/daterangepicker.min.css" />
    <link rel="stylesheet" href="/assets/css/bootstrap-datetimepicker.min.css" />
@stop


@section('content')
    <x-mm.styles />
    <x-mm.page class="mm-dashboard" title="Access Denied">
        <x-mm.panel>
    <div class="row">
        <div class="col-xs-12">


            
<div class="main-content">
				<div class="main-content-inner">
					

					<div class="page-content">
						
						<div class="row">
							<div class="col-xs-12">
								<!-- PAGE CONTENT BEGINS -->

								<div class="error-container">
									<div class="well">
										<h1 class="grey lighter smaller">
											<span class="blue bigger-125">
												<i class="ace-icon fa fa-sitemap"></i>
												403
											</span>
											Page Not Found
										</h1>

										<hr />
										<h3 class="lighter smaller">You do not have the authority system to use this system!</h3>

										<div>
											<form class="form-search">
												<span class="input-icon align-middle">
													<i class="ace-icon fa fa-search"></i>

													<input type="text" class="search-query" placeholder="Give it a search..." />
												</span>
												<button class="btn btn-sm" type="button">Go!</button>
											</form>

											<div class="space"></div>
											<h4 class="smaller">Try one of the following:</h4>

											<ul class="list-unstyled spaced inline bigger-110 margin-15">
												<li>
													<i class="ace-icon fa fa-hand-o-right blue"></i>
													Re-check the url for typos
												</li>

												<li>
													<i class="ace-icon fa fa-hand-o-right blue"></i>
													Read the faq
												</li>

												<li>
													<i class="ace-icon fa fa-hand-o-right blue"></i>
													Tell us about it
												</li>
											</ul>
										</div>

										<hr />
										<div class="space"></div>

										<div class="center">
											<a href="{{ url()->previous() }}" class="btn btn-grey">
												<i class="ace-icon fa fa-arrow-left"></i>
												Go Back
											</a>

											<a href="/home" class="btn btn-primary">
												<i class="ace-icon fa fa-tachometer"></i>
												Dashboard
											</a>
										</div>
									</div>
								</div>

								<!-- PAGE CONTENT ENDS -->
							</div><!-- /.col -->
						</div><!-- /.row -->
					</div><!-- /.page-content -->
				</div>
            </div><!-- /.main-content -->
            
        </div><!-- /.col -->
    </div>
        </x-mm.panel>
    </x-mm.page>


@endsection

@section('js')



    


@stop



