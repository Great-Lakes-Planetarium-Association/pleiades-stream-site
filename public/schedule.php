					<div id="schedule" class="row">
						<div class="column small-12 medium-4">
							<h2>Schedule</h2>
							<ul class="vertical tabs" data-tabs id="schedule-content">
								<li class="tabs-title is-active">
									<a href="#schedule-0" aria-selected="true">
										Thursday, October 20<sup>th</sup>
									</a>
								</li>
								<li class="tabs-title">
									<a href="#schedule-1">
										Friday, October 21<sup>st</sup>
									</a>
								</li>
							</ul>
						</div>
						<div class="column small-12 medium-8">
							<p class="text-right">
								Current Time:
								<span class="time"><?php include_once(__DIR__ . '/datetime.php'); ?></span>
							</p>
							<div class="tabs-content" data-tabs-content="schedule-content">
								<div class="tabs-panel is-active" id="schedule-0">
									<div class="table-scroll">
										<table class="stacked">
											<thead>
												<tr>
													<th>Time</th>
													<th>Title</th>
													<th>Presenter</th>
												</tr>
											</thead>
											<tbody>
												<tr>
													<td>
														9:00 PM - 9:15 PM
													</td>
													<td>
														Title
													</td>
													<td>
														Presenter
													</td>
												</tr>
												<tr class="highlight">
													<td>
														9:15 PM - 9:45 PM
													</td>
													<td>
														Title
													</td>
													<td>
														Presenter
													</td>
												</tr>
												<tr>
													<td>
														9:45 PM - 10:00 PM
													</td>
													<td>
														Title
													</td>
													<td>
														Presenter
													</td>
												</tr>
											</tbody>
										</table>
									</div>
								</div>
								<div class="tabs-panel" id="schedule-1">

								</div>
							</div>
						</div>
					</div>