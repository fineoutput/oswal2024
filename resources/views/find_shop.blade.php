@extends('layouts.app')

@section('title', $title ?? '')

@section('content')
<div class="container">
        <div class="row">
            <div class="col-lg-12 col-sm-12">
                <h2 class="text-center">Oswal Retail Shops</h2>
            </div>
        </div>
    </div>

    <div class="container" style="margin-top:10px;">
        <table id="example" class="table table-striped table-bordered table-responsive" style="width:100%">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Shop Name</th>
                    <th>Person Name	</th>
                    <th>Address	</th>
                    <th>City</th>
                    <th>State</th>
                    <th>Pincode</th>
                    <th>Landline</th>
                    <th>Mobile</th>
                </tr>
            </thead>
            <tbody>
                <tr class="table-primary">
                    <td>1</td>
                    <td>Adinath Trading Comapny	Satishji </td>
                    <td>Satishji Tomar</td>
                    <td>199, Shop No. 2, Vaishali Nagar, Jaipur, Jaipur, Rajasthan</td>
                    <td>Jaipur</td>
                    <td>Rajasthan</td>
                    <td>302021	</td>
                    <td>0141-2354410</td>
                    <td>6376951784</td>
                </tr>
                <tr class="table-secondary">
                    <td>2</td>
                    <td>Ajay Trading Company</td>
                    <td>Nirmal Ji Pahadiya</td>
                    <td>MSB KA RASTA, JOHARI BAZAAR, JAIPUR, Jaipur,Rajasthan</td>
                    <td>Jaipur</td>
                    <td>Rajasthan</td>
                    <td>302003</td>
                    <td>0141-2569983</td>
                    <td>9928239827</td>
                </tr>
                <tr class="table-primary">
                    <td>3</td>
                    <td>Amol Trading Company</td>
                    <td>Deepu Bhaiya</td>
                    <td>Opp Sector No. 1, Malviya nagar, Jaipur, Jaipur, Rajasthan	</td>
                    <td>Jaipur</td>
                    <td>Rajasthan</td>
                    <td>302017</td>
                    <td>0141-2759466	</td>
                    <td>8560841420</td>
                </tr>
                <tr class="table-secondary">
                    <td>4</td>
                    <td>Ankit Trading Company	</td>
                    <td>Babulalji</td>
                    <td>203, RAM NAGAR, SHOPPING CENTRE, SHASTRI NAGAR,Jaipur, Rajasthan, 302016	</td>
                    <td>Jaipur</td>
                    <td>Rajasthan</td>
                    <td>302016</td>
                    <td>0141-2302974	</td>
                    <td>0141-23029
                    </td>
                </tr>
                <tr class="table-primary">
                    <td>5</td>
                    <td>Dhruv Trading Company</td>
                    <td>Rakeshji</td>
                    <td>D-23, 80 Feet Road, Mahesh Nagar, Jaipur, Rajasthan,	</td>
                    <td>Jaipur</td>
                    <td>Rajasthan</td>
                    <td>302015</td>
                    <td>0141-2450922	</td>
                    <td>9461281557
                    </td>
                </tr>
                <tr class="table-secondary">
                    <td>6</td>
                    <td>Gaurav Trading Company	</td>
                    <td>Ikbalji</td>
                    <td>Gayatri Nagar, Sodala, Ajmer Road, Jaipur, Rajasthan</td>
                    <td>Jaipur</td>
                    <td>Rajasthan</td>
                    <td>302019</td>
                    <td>9784886107		</td>
                    <td>0141-2450922
                    </td>
                </tr>
                <tr class="table-primary">
                    <td>7</td>
                    <td>Hemank Trading Company	</td>
                    <td>Kamalji	</td>
                    <td>16, Govindpura, Kardhani, Kalwar Road, Jaipur, Rajasthan	</td>
                    <td>Jaipur</td>
                    <td>Rajasthan</td>
                    <td>302012</td>
                    <td>9694333825		</td>
                    <td>
                    </td>
                </tr>
                <tr class="table-secondary">
                    <td>8</td>
                    <td>Indra Trading Company	</td>
                    <td>Rajeshji	</td>
                    <td>Road No. 3, VKI Area, Jaipur, Jaipur, Rajasthan	</td>
                    <td>Jaipur</td>
                    <td>Rajasthan</td>
                    <td>302013</td>
                    <td>9929961956</td>
                    <td>9782002917
                    </td>
                </tr>
                <tr class="table-primary">
                    <td>9</td>
                    <td>Oswal Soap Agency	</td>
                    <td>Sanjay Khan		</td>
                    <td>CC-5, Jawahar Nagar, Sabji Mandi, Jaipur, Rajasthan	</td>
                    <td>Jaipur</td>
                    <td>Rajasthan</td>
                    <td>302004</td>
                    <td>0141-2657955	</td>
                    <td>9828371387
                    </td>
                </tr>
                <tr class="table-secondary">
                    <td>10</td>
                    <td>Sanjay Trading Company	</td>
                    <td>Shivcharanji</td>
                    <td>30/5/1, Madhyam Marg, Mansarover, Jaipur, Rajasthan	</td>
                    <td>Jaipur</td>
                    <td>Rajasthan</td>
                    <td>302020</td>
                    <td>0141-2392519	</td>
                    <td>7891047318
                    </td>
                </tr>
                <tr class="table-primary">
                    <td>11</td>
                    <td>Shivali Trading Company	</td>
                    <td>Firoj Khan	</td>
                    <td>H-332 D, VKI AREA, Jaipur, Jaipur, Rajasthan	</td>
                    <td>Jaipur</td>
                    <td>Rajasthan</td>
                    <td>302013</td>
                    <td></td>
                    <td>9057860462
                    </td>
                </tr>
                <tr class="table-secondary">
                    <td>12</td>
                    <td>Saurabh Trading Company	</td>
                    <td>Omprakash Jayswal	</td>
                    <td>16, Gokul market, Khatipura Road, Jhotwara, Jaipur, Rajasthan	</td>
                    <td>Jaipur</td>
                    <td>Rajasthan</td>
                    <td>302012</td>
                    <td>0141-2345320	</td>
                    <td>9414696896
                    </td>
                </tr>
                <tr class="table-primary"><td>13</td>
                    <td>Shrenik Trading Company</td>
                    <td>Pankaj ji</td>
                    <td>11-B, JAI AMBEY NAGAR, TONK ROAD, Jaipur, Rajasthan</td>
                
                    <td>Jaipur</td>
                    <td>Rajasthan</td>
                    <td>302011</td>
                      <td>0141-2554415</td>
                      <td>9672642139</td>
                    <!-- <td>
                            <a class="btn btn-info cticket" href="https://www.oswalsoap.com/home/16" role="button" style="margin-bottom:12px;">Shop Details</a>
                    </td> -->
                  </tr>
                  <tr class="table-secondary"><td>14</td>
                    <td>Eight Brothers Sales Pvt. Ltd.</td>
                    <td>Jitendraji</td>
                    <td>Shop No. 19, L.C. Yogana, Ajmer Road, Bhankrota, Jaipur, Rajasthan</td>
                
                    <td>Jaipur</td>
                    <td>Rajasthan</td>
                    <td>302026</td>
                      <td>0141-2250059</td>
                      <td>9782092006</td>
                    <!-- <td>
                            <a class="btn btn-info cticket" href="https://www.oswalsoap.com/home/17" role="button" style="margin-bottom:12px;">Shop Details</a>
                    </td> -->
                  </tr>
                  <tr class="table-primary"><td>15</td>
                    <td>Eight Brothers Sales Pvt. Ltd.</td>
                    <td>Premprakashji</td>
                    <td>Shop No. 21, Sanni Nagar, Pratap Nagar, Housing Board Sanganer, Jaipur, Rajasthan</td>
                
                    <td>Jaipur</td>
                    <td>Rajasthan</td>
                    <td>302033</td>
                      <td>0141-2170458</td>
                      <td>9352795425</td>
                    <!-- <td>
                            <a class="btn btn-info cticket" href="https://www.oswalsoap.com/home/18" role="button" style="margin-bottom:12px;">Shop Details</a>
                    </td> -->
                  </tr>
                  <tr class="table-secondary"><td>16</td>
                    <td>Eight Brothers Sales Pvt. Ltd.</td>
                    <td>Rajendraji</td>
                    <td>Shop No. 3, Ganpati Tower, Central Spine, Vidhyadhar Nagar, Jaipur, Rajasthan</td>
                
                    <td>Jaipur</td>
                    <td>Rajasthan</td>
                    <td>302039</td>
                      <td>0141-2336470</td>
                      <td>7610048886</td>
                    <!-- <td>
                            <a class="btn btn-info cticket" href="https://www.oswalsoap.com/home/19" role="button" style="margin-bottom:12px;">Shop Details</a>
                    </td> -->
                  </tr>
                  <tr class="table-primary"><td>17</td>
                    <td>Eight Brothers Sales Pvt. Ltd.</td>
                    <td>Omprakashji</td>
                    <td>23-24, Guruteg Bahadur Nagar, Goner Road, Jaipur, Rajasthan</td>
                
                    <td>Jaipur</td>
                    <td>Rajasthan</td>
                    <td>303012</td>
                      <td>0141-2682415</td>
                      <td>9461967211</td>
                    <!-- <td>
                            <a class="btn btn-info cticket" href="https://www.oswalsoap.com/home/20" role="button" style="margin-bottom:12px;">Shop Details</a>
                    </td> -->
                  </tr>
                  <tr class="table-secondary"><td>18</td>
                    <td>Eight Brothers Sales Pvt. Ltd.</td>
                    <td>Subodhji</td>
                    <td>Shop No-13, Ridhi Sidhi Nagar, Gram Muhana, Tehsil Sanganer, Jaipur, Rajasthan</td>
                
                    <td>Jaipur</td>
                    <td>Rajasthan</td>
                    <td>302033</td>
                      <td></td>
                      <td>9784038237</td>
                    <!-- <td>
                            <a class="btn btn-info cticket" href="https://www.oswalsoap.com/home/21" role="button" style="margin-bottom:12px;">Shop Details</a>
                    </td> -->
                  </tr>
                  <tr class="table-primary"><td>19</td>
                    <td>Uttam Chand Desraj </td>
                    <td></td>
                    <td>174/175, Chandpole Bazaar, Jaipur, Rajasthan</td>
                
                    <td>Jaipur</td>
                    <td>Rajasthan</td>
                    <td>302001</td>
                      <td>0141-2314857</td>
                      <td></td>
                    <!-- <td>
                            <a class="btn btn-info cticket" href="https://www.oswalsoap.com/home/22" role="button" style="margin-bottom:12px;">Shop Details</a>
                    </td> -->
                  </tr>
                  <tr class="table-secondary"><td>20</td>
                    <td>Uttam Chand Desraj </td>
                    <td>Sunilji</td>
                    <td>292, Chandpole Bazaar, Jaipur, Rajasthan</td>
                
                    <td>Jaipur</td>
                    <td>Rajasthan</td>
                    <td>302001</td>
                      <td>0141-2310658</td>
                      <td>9460388266</td>
                    <!-- <td>
                            <a class="btn btn-info cticket" href="https://www.oswalsoap.com/home/23" role="button" style="margin-bottom:12px;">Shop Details</a>
                    </td> -->
                  </tr>
                  <tr class="table-primary"><td>21</td>
                    <td>Prabhat Trading Company</td>
                    <td>Budhiprakashji</td>
                    <td>Shop No. 6 , 7, Kishan Market, Sanganer, Jaipur, Rajasthan</td>
                
                    <td>Jaipur</td>
                    <td>Rajasthan</td>
                    <td>302033</td>
                      <td>0141-2733858</td>
                      <td>8385072727</td>
                    <!-- <td>
                            <a class="btn btn-info cticket" href="https://www.oswalsoap.com/home/24" role="button" style="margin-bottom:12px;">Shop Details</a>
                    </td> -->
                  </tr>
                  <tr class="table-secondary"><td>22</td>
                    <td>Prabhat Trading Company</td>
                    <td>Tarachandji</td>
                    <td>G-10, Near Bus Stand, Rathi Mension, Ajmer Road, Madanganj, Ajmer, Rajasthan</td>
                
                    <td>Ajmer</td>
                    <td>Rajasthan</td>
                    <td>305801</td>
                      <td></td>
                      <td>7742450825</td>
                    <!-- <td>
                            <a class="btn btn-info cticket" href="https://www.oswalsoap.com/home/25" role="button" style="margin-bottom:12px;">Shop Details</a>
                    </td> -->
                  </tr>
                  <tr class="table-primary"><td>23</td>
                    <td>Basant Trading Company </td>
                    <td>Ghanshyamji</td>
                    <td>GH - 7, Suraj Pole mandi, Jaipur, Rajasthan</td>
                
                    <td>Jaipur</td>
                    <td>Rajasthan</td>
                    <td>302003</td>
                      <td>0141-2642125</td>
                      <td>9314090086</td>
                    <!-- <td>
                            <a class="btn btn-info cticket" href="https://www.oswalsoap.com/home/26" role="button" style="margin-bottom:12px;">Shop Details</a>
                    </td> -->
                  </tr>
                  <tr class="table-secondary"><td>24</td>
                    <td>Basant Trading Company </td>
                    <td></td>
                    <td>Shop No. 51, Kayasth Market, M G Hospital Road, Jodhpur, Rajasthan</td>
                
                    <td>Jodhpur</td>
                    <td>Rajasthan</td>
                    <td>342001</td>
                      <td></td>
                      <td></td>
                    <!-- <td>
                            <a class="btn btn-info cticket" href="https://www.oswalsoap.com/home/27" role="button" style="margin-bottom:12px;">Shop Details</a>
                    </td> -->
                  </tr>
                  <tr class="table-primary"><td>25</td>
                    <td>Basant Trading Company </td>
                    <td>Fareedji</td>
                    <td>Shop No 1, Al Rahis Shopping Complex, Moti Chowk, Jodhpur, Rajasthan</td>
                
                    <td>Jodhpur</td>
                    <td>Rajasthan</td>
                    <td>342001</td>
                      <td></td>
                      <td>9413955780</td>
                    <!-- <td>
                            <a class="btn btn-info cticket" href="https://www.oswalsoap.com/home/28" role="button" style="margin-bottom:12px;">Shop Details</a>
                    </td> -->
                  </tr>
                  <tr class="table-secondary"><td>26</td>
                    <td>Basant Trading Company </td>
                    <td>Roshanji</td>
                    <td>Shop No 3, Opp Rawat Bhata Bus Stand, Near Petrol Pump, Shakawat Shopping Complex, Gumanpura,Kota, Rajasthan</td>
                
                    <td>Kota</td>
                    <td>Rajasthan</td>
                    <td>324007</td>
                      <td></td>
                      <td>8239716427</td>
                    <!-- <td>
                            <a class="btn btn-info cticket" href="https://www.oswalsoap.com/home/29" role="button" style="margin-bottom:12px;">Shop Details</a>
                    </td> -->
                  </tr>
                  <tr class="table-primary"><td>27</td>
                    <td>Basant Trading Company </td>
                    <td>Shivprakashji</td>
                    <td>Shop No 4, Opp Town Police Chowki, Railway Station Road, Makrana, Nagaur, Rajasthan</td>
                
                    <td>Nagaur</td>
                    <td>Rajasthan</td>
                    <td>341505</td>
                      <td>01588-247320</td>
                      <td>9785844598</td>
                    <!-- <td>
                            <a class="btn btn-info cticket" href="https://www.oswalsoap.com/home/30" role="button" style="margin-bottom:12px;">Shop Details</a>
                    </td> -->
                  </tr>
                  <tr class="table-secondary"><td>28</td>
                    <td>Basant Trading Company </td>
                    <td>Murariji</td>
                    <td>Near Sales Tax Office, Bundi Deoli Bye Pass Road, Bundi, Bundi, Rajasthan</td>
                
                    <td>Bundi</td>
                    <td>Rajasthan</td>
                    <td>323001</td>
                      <td></td>
                      <td>9214306974</td>
                    <!-- <td>
                            <a class="btn btn-info cticket" href="https://www.oswalsoap.com/home/31" role="button" style="margin-bottom:12px;">Shop Details</a>
                    </td> -->
                  </tr>
            </tbody>
          
        </table>
    </div>

    @endsection
    
