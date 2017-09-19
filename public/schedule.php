				<?php include(__DIR__ . '/../private/json.php'); ?>
				<?php
					//If there are events.
					if (isset($eventSet) && count($eventSet) > 0) {
				?>
					<div id="schedule" class="row">
						<div class="column small-12 medium-3">
							<h2>Schedule</h2>
							<ul class="vertical tabs" data-tabs id="schedule-content">
							<?php foreach($eventSet as $k => $day) { ?>
								<li class="tabs-title<?php if (_vc($day, 'active')) { ?> is-active<?php } ?>">
									<a href="#schedule-<?php print($k); ?>" aria-selected="true">
										<?php print(_vc($day, 'dayString')); ?>
									</a>
								</li>
							<?php } ?>
							</ul>
						</div>
						<div class="column small-12 medium-9">
							<p class="text-right">
								Current Time:
								<span class="time"><?php include_once(__DIR__ . '/datetime.php'); ?></span>
							</p>
							<div class="tabs-content" data-tabs-content="schedule-content">
							<?php foreach($eventSet as $k => $day) { ?>
								<div class="tabs-panel <?php if (_vc($event, 'active')) { ?> is-active<?php } ?>"
									id="schedule-<?php print($k); ?>">
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
										<?php if (count(_vc($day, 'events')) > 0 ) { ?>
											<?php foreach(_vc($day, 'events') as $k => $event) { ?>
												<?php $tsSrt = strtotime(_vc($day, 'date') . _vc($event, 'start_time')); ?>
												<?php $tsEnd = strtotime(_vc($day, 'date') . _vc($event, 'end_time')); ?>
												<?php $hlght = (time() > $tsSrt && time() < $tsEnd) ? true : false; ?>
												<tr<?php if ($hlght) { ?> class="highlight"<?php } ?>
													data-hls="<?php ?>"
													data-rtmp="<?php ?>"
													data-youtube="<?php ?>"
													data-audio="<?php ?>"
													data-ustream="<?php ?>">
													<td>
														<?php printf("%s - %s",
																date('h:i A', $tsSrt),
																date('h:i A', $tsEnd)); ?>
													</td>
													<td><?php print(_vc($event, 'title')); ?></td>
													<td><?php print(_vc($event, 'start_time')); ?></td>
												</tr>
											<?php } ?>
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
							<?php } ?>
						<?php } ?>
							</div>
						</div>
					</div>
				<?php } ?>