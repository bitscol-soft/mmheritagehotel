  <li>
      <a href="javascript:void(0)" class="dropdown-toggle" title="Front Desk">
          <i class="menu-icon fas fa-hotel"></i>
          Front Desk
          <b class="arrow fa fa-angle-down"></b>
      </a>
      <b class="arrow"></b>
      <ul class="submenu">


          <li>
              <a href="javascript:void(0)" class="dropdown-toggle">
                  <i class="menu-icon fa fa-caret-right"></i>
                  <span class="menu-text">Booking</span>
                  <b class="arrow fa fa-angle-down"></b>
              </a>
              <b class="arrow"></b>

              <ul class="submenu">

                  @if (hasPermission('bookings.create', $slugs))
                      <li class="{{ request()->is('hotel/booking/create') ? 'active' : '' }}">
                          <a href="{{ route('booking.create') }}">
                              <i class="menu-icon fa fa-caret-right"></i>
                              Create
                          </a>
                          <b class="arrow"></b>
                      </li>
                      <li class="{{ request()->is('hotel/booking/create') ? 'active' : '' }}">
                          <a href="{{ route('booking.ui') }}">
                              <i class="menu-icon fa fa-caret-right"></i>
                              Booking UI
                          </a>
                          <b class="arrow"></b>
                      </li>
                  @endif

                  @if (hasPermission('bookings.index', $slugs))
                      <li class="{{ request()->is('hotel/booking') ? 'active' : '' }}">
                          <a href="{{ route('booking.index') }}">
                              <i class="menu-icon fa fa-caret-right"></i>
                              Booking List
                          </a>
                          <b class="arrow"></b>
                      </li>
                      <li class="{{ request()->is('hotel/booking') ? 'active' : '' }}">
                          <a href="{{ route('booking.referred-booking') }}">
                              <i class="menu-icon fa fa-caret-right"></i>
                              Referred Booking
                          </a>
                          <b class="arrow"></b>
                      </li>
                      <li
                          class="{{ request()->is('hotel/booking-purpose') && request('type') == 'purpose' ? 'active' : '' }}">
                          <a href="{{ route('booking-purpose.index') }}?type=purpose">
                              <i class="menu-icon fa fa-caret-right"></i>
                              Booking Purpose
                          </a>
                          <b class="arrow"></b>
                      </li>
                      <li
                          class="{{ request()->is('hotel/booking-purpose') && request('type') == 'platform' ? 'active' : '' }}">
                          <a href="{{ route('booking-purpose.index') }}?type=platform">
                              <i class="menu-icon fa fa-caret-right"></i>
                              Booking Platform
                          </a>
                          <b class="arrow"></b>
                      </li>
                  @endif


              </ul>
          </li>


          <li>
              <a href="javascript:void(0)" class="dropdown-toggle">
                  <i class="menu-icon fa fa-caret-right"></i>
                  <span class="menu-text">Guests</span>
                  <b class="arrow fa fa-angle-down"></b>
              </a>
              <b class="arrow"></b>

              <ul class="submenu">
                  @if (hasPermission('guests.create', $slugs))
                      <li class="">
                          <a href="{{ route('guests.create') }}">
                              <i class="menu-icon fa fa-caret-right"></i>
                              Guest Registration
                          </a>
                          <b class="arrow"></b>
                      </li>
                  @endif

                  @if (hasPermission('guests.index', $slugs))
                      <li class="">
                          <a href="{{ route('guests.index') }}">
                              <i class="menu-icon fa fa-caret-right"></i>
                              Guest List
                          </a>
                          <b class="arrow"></b>
                      </li>
                  @endif


                  <!--------- SEND SMS --------->
                  <li class="">
                      <a href="{{ route('guests.send-sms') }}">
                          <i class="menu-icon fa fa-caret-right"></i>
                          Send SMS
                      </a>
                  </li>


              </ul>
          </li>


          <li>
              <a href="javascript:void(0)" class="dropdown-toggle">
                  <i class="menu-icon fa fa-caret-right"></i>
                  <span class="menu-text">Room Management</span>
                  <b class="arrow fa fa-angle-down"></b>
              </a>
              <b class="arrow"></b>

              <ul class="submenu">
                  @if (hasPermission('categories.index', $slugs))
                      <li class="">
                          <a href="javascript:void(0)" class="dropdown-toggle">
                              <i class="menu-icon fa fa-caret-right"></i>
                              <span class="menu-text">Category</span>
                              <b class="arrow fa fa-angle-down"></b>
                          </a>
                          <b class="arrow"></b>
                          <ul class="submenu">
                              @if (hasPermission('categories.create', $slugs))
                                  <li>
                                      <a href="{{ route('hotel-categories.create') }}">
                                          <i class="menu-icon fa fa-caret-right"></i>
                                          Create
                                      </a>
                                  </li>
                              @endif
                              <li>
                                  <a href="{{ route('hotel-categories.index') }}">
                                      <i class="menu-icon fa fa-caret-right"></i>
                                      List
                                  </a>
                              </li>
                          </ul>
                      </li>
                  @endif

                  @if (hasPermission('aminities.index', $slugs))
                      <li class="">
                          <a href="javascript:void(0)" class="dropdown-toggle">
                              <i class="menu-icon fa fa-caret-right"></i>
                              <span class="menu-text">Amenities</span>
                              <b class="arrow fa fa-angle-down"></b>
                          </a>
                          <b class="arrow"></b>

                          <ul class="submenu">
                              <li>
                                  <a href="{{ route('aminities.index') }}">
                                      <i class="menu-icon fa fa-caret-right"></i>
                                      List
                                  </a>
                              </li>

                              @if (hasPermission('aminities.create', $slugs))
                                  <li>
                                      <a href="{{ route('aminities.create') }}">
                                          <i class="menu-icon fa fa-caret-right"></i>
                                          Create
                                      </a>
                                  </li>
                              @endif
                          </ul>
                      </li>
                  @endif

                  @if (hasPermission('rooms.index', $slugs))
                      <li class="">
                          <a href="{{ route('rooms.index') }}">
                              <i class="menu-icon fa fa-caret-right"></i>
                              Rooms
                          </a>
                          <b class="arrow"></b>
                      </li>
                  @endif


              </ul>
          </li>

          <!-- PAYMENT COLLECTION -->
          @if (hasPermission('hotel.booking-collection', $slugs))
              <li>
                  <a href="{{ route('booking-collection') }}">
                      <i class="menu-icon fa fa-caret-right"></i>
                      Payment Collection
                  </a>
                  <b class="arrow"></b>
              </li>
          @endif

          @if (hasPermission('hotel.night-audit.index', $slugs))
              <li>
                  <a href="{{ route('night-audits.index') }}">
                      <i class="menu-icon fa fa-caret-right"></i>
                      Night Audits
                  </a>
                  <b class="arrow"></b>
              </li>
          @endif





          <li>
              <a href="javascript:void(0)" class="dropdown-toggle">
                  <i class="menu-icon fa fa-caret-right"></i>
                  <span class="menu-text">Report</span>
                  <b class="arrow fa fa-angle-down"></b>
              </a>
              <b class="arrow"></b>

              <ul class="submenu">

                  @if (hasPermission('hotel.cash-flow-report.index', $slugs))
                      <li>
                          <a href="{{ route('report.cash-flows') }}">
                              <i class="menu-icon fa fa-caret-right"></i>
                              Cash Flow
                          </a>
                          <b class="arrow"></b>
                      </li>
                  @endif
                  @if (hasPermission('hotel.night-audit-report.index', $slugs))
                      <li>
                          <a href="{{ route('report.night-audit') }}">
                              <i class="menu-icon fa fa-caret-right"></i>
                              Night Audit
                          </a>
                          <b class="arrow"></b>
                      </li>
                  @endif
                  @if (hasPermission('hotel.monthly-report.index', $slugs))
                      <li>
                          <a href="{{ route('report.monthly-booking') }}">
                              <i class="menu-icon fa fa-caret-right"></i>
                              Monthly Booking UI
                          </a>
                          <b class="arrow"></b>
                      </li>

                      <li>
                          <a href="{{ route('report.monthly') }}">
                              <i class="menu-icon fa fa-caret-right"></i>
                              Monthly Booking Report
                          </a>
                          <b class="arrow"></b>
                      </li>
                  @endif


                  <!-------------- EXPECTED ARRIVAL/PICKUP  --------------->
                  @if (hasPermission('hotel.expected-arrival.index', $slugs))
                      <li>
                          <a href="{{ route('report.expected-arrival') }}">
                              <i class="menu-icon fa fa-caret-right"></i>
                              Expected Arrival
                          </a>
                          <b class="arrow"></b>
                      </li>
                  @endif


                  <!-------------- EXPECTED DEPARTURE/DROP  --------------->
                  @if (hasPermission('hotel.expected-departure.index', $slugs))
                      <li>
                          <a href="{{ route('report.expected-departure') }}">
                              <i class="menu-icon fa fa-caret-right"></i>
                              Expected Departure
                          </a>
                          <b class="arrow"></b>
                      </li>
                  @endif


                  <!-------------- IN HOUSE GUEST LIST [GUEST WHOES BOOKING STATUS IS CHECKED IN]  --------------->
                  @if (hasPermission('hotel.in-house-guest.index', $slugs))
                      <li>
                          <a href="{{ route('report.in-house-guest') }}">
                              <i class="menu-icon fa fa-caret-right"></i>
                              In House Guest
                          </a>
                          <b class="arrow"></b>
                      </li>
                  @endif



                  @if (hasPermission('hotel.monthly-report.index', $slugs))
                      <li>
                          <a href="{{ route('report.room-log') }}">
                              <i class="menu-icon fa fa-caret-right"></i>
                              Room Log Report
                          </a>
                          <b class="arrow"></b>
                      </li>
                  @endif

                  @if (hasPermission('hotel.monthly-report.index', $slugs))
                      <li>
                          <a href="{{ route('report.service') }}">
                              <i class="menu-icon fa fa-caret-right"></i>
                              Service Report
                          </a>
                          <b class="arrow"></b>
                      </li>
                  @endif
                  @if (hasPermission('hotel.monthly-report.index', $slugs))
                      <li>
                          <a href="{{ route('report.today-activities') }}">
                              <i class="menu-icon fa fa-caret-right"></i>
                              Today Report
                          </a>
                          <b class="arrow"></b>
                      </li>
                  @endif

                  @if (hasPermission('hotel.daily-check-in', $slugs))
                      <li>
                          <a href="{{ route('report.daily-check-in') }}">
                              <i class="menu-icon fa fa-caret-right"></i>
                              Daily Check In
                          </a>
                          <b class="arrow"></b>
                      </li>
                  @endif
                  @if (hasPermission('hotel.daily-check-out', $slugs))
                      <li>
                          <a href="{{ route('report.daily-check-out') }}">
                              <i class="menu-icon fa fa-caret-right"></i>
                              Daily Check Out
                          </a>
                          <b class="arrow"></b>
                      </li>
                  @endif

                  @if (hasPermission('report.today-in-house', $slugs))
                      <li>
                          <a href="{{ route('report.today-in-house') }}">
                              <i class="menu-icon fa fa-caret-right"></i>
                              Today In House List
                          </a>
                          <b class="arrow"></b>
                      </li>
                  @endif


                  @if (hasPermission('hotel.report.over-all', $slugs))
                      <li>
                          <a href="{{ route('report.report.over-all') }}">
                              <i class="menu-icon fa fa-caret-right"></i>
                              Over All Reports
                          </a>
                          <b class="arrow"></b>
                      </li>
                  @endif
              </ul>



        <!-------------- VAT REPORTS --------------->
              <ul class="submenu">
                @if (hasPermission('hotel.report.over-all', $slugs))
                    <li class="">
                        <a href="javascript:void(0)" class="dropdown-toggle">
                            <i class="menu-icon fa fa-caret-right"></i>
                            <span class="menu-text">Vat Reports</span>
                            <b class="arrow fa fa-angle-down"></b>
                        </a>
                        <b class="arrow"></b>
                        <ul class="submenu">
                            @if (hasPermission('categories.create', $slugs))
                                <li>
                                    <a href="{{ route('report.vatDaily') }}">
                                        <i class="menu-icon fa fa-caret-right"></i>
                                        Daily Vat Report
                                    </a>
                                </li>
                            @endif
                            <li>
                                <a href="{{ route('report.vatMonthly') }}">
                                    <i class="menu-icon fa fa-caret-right"></i>
                                    Monthly Vat Report
                                </a>
                            </li>
                        </ul>
                    </li>
                @endif

            </ul>
          </li>

          <li>
              <a href="javascript:void(0)" class="dropdown-toggle">
                  <i class="menu-icon fa fa-caret-right"></i>
                  <span class="menu-text">Setting</span>
                  <b class="arrow fa fa-angle-down"></b>
              </a>
              <b class="arrow"></b>

              <ul class="submenu">

                  @if (hasPermission('vats.index', $slugs))
                      <li class="">
                          <a href="{{ route('vat.index') }}">
                              <i class="menu-icon fa fa-caret-right"></i>
                              Vat & Services
                          </a>
                          <b class="arrow"></b>
                      </li>
                  @endif

                  @if (hasPermission('account.types.index', $slugs))
                      <li class="">
                          <a href="{{ route('account-type.index') }}">
                              <i class="menu-icon fa fa-caret-right"></i>
                              Account Type
                          </a>
                          <b class="arrow"></b>
                      </li>
                  @endif

                  <li>
                      <a href="{{ route('booking-note.index') }}">
                          <i class="menu-icon fa fa-caret-right"></i>
                          Booking Note
                      </a>
                  </li>

                  <li>
                      <a href="{{ route('guest-registration-terms.index') }}">
                          <i class="menu-icon fa fa-caret-right"></i>
                          Terms & Condition
                      </a>
                  </li>

                  <!-- Currency Conversion -->
                  @if (hasPermission('currency-conversions.index', $slugs))
                      <li class="{{ request()->is('currency-conversions') ? 'active' : '' }}">
                          <a href="{{ route('currency-conversions.index') }}">
                              <i class="menu-icon fa fa-caret-right"></i>
                              Currency Conversion
                          </a>
                          <b class="arrow"></b>
                      </li>
                  @endif

              </ul>
          </li>

      </ul>
  </li>



  <li>
      <a href="{{ route('Booking.HouseKeeping') }}" title="House Keeping">
          <i class="menu-icon fas fa-door-closed"></i>
          House Keeping
          <b class="arrow fa fa-angle-down"></b>
      </a>
      <b class="arrow"></b>
  </li>
