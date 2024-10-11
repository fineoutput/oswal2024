@extends('layouts.app')

@section('title', $title ?? '')

@section('content')
<style>
  .backkks_shop{
    background-position: center;
    background-size:cover;
    background-repeat: no-repeat;
  }
  .table-responsive {
    overflow-x: auto !important;
    -webkit-overflow-scrolling: touch !important;
}

</style>
<div class="backkks_shop" style="background-image: url('{{ asset('images/oswal_shop_shop.png') }}');">
<div class="container">
        <div class="row">
            <div class="col-lg-12 col-sm-12">
                <h2 class="text-center">Oswal Retail Shops</h2>
            </div>
        </div>
    </div>

    <div class="container section-padding" style="margin-top:10px;">
        <div class="table-responsive">
        <table id="example" class="table table-striped table-bordered" style="width:100%">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Shop Name</th>
                    <th>Person Name	</th>
                    <th>Address	</th>
                    <th>City</th>
                    <th>State</th>
                    <th>Pincode</th>
                    <!-- <th>Landline</th> -->
                    <th>Mobile</th>
                </tr>
            </thead>
            <tbody>
    <tr class="table-primary">
        <td>1</td>
        <td>Adinath Trading Company</td>
        <td>Satish Tomar</td>
        <td>199, Shop No. 2, Vaishali Nagar, Jaipur, Jaipur, Rajasthan, 302021</td>
        <td>Jaipur</td>
        <td>Rajasthan</td>
        <td>302021</td>
        <!-- <td></td> -->
        <td>6376951784</td>
    </tr>
    <tr class="table-secondary">
        <td>2</td>
        <td>Ajay Trading Company</td>
        <td>Nirmal Pahadiya</td>
        <td>MSB KA RASTA, JOHARI BAZAAR, JAIPUR, Jaipur, Rajasthan, 302003</td>
        <td>Jaipur</td>
        <td>Rajasthan</td>
        <td>302003</td>
        <!-- <td></td> -->
        <td>9928239827</td>
    </tr>
    <tr class="table-primary">
        <td>3</td>
        <td>Amol Trading Company</td>
        <td>Kuldeep Khatter</td>
        <td>Opp Sector No. 1, Malviya nagar, Jaipur, Jaipur, Rajasthan, 302017</td>
        <td>Jaipur</td>
        <td>Rajasthan</td>
        <td>302017</td>
        <!-- <td></td> -->
        <td>8560841420</td>
    </tr>
    <tr class="table-secondary">
        <td>4</td>
        <td>Ankit Trading Company</td>
        <td>Babulal Sharma</td>
        <td>203, RAM NAGAR, SHOPPING CENTRE, SHASTRI NAGAR, Jaipur, Rajasthan, 302016</td>
        <td>Jaipur</td>
        <td>Rajasthan</td>
        <td>302016</td>
        <!-- <td></td> -->
        <td>8386969074</td>
    </tr>
    <tr class="table-primary">
        <td>5</td>
        <td>Dhruv Trading Company</td>
        <td>Ratan Jat</td>
        <td>D-23, 80 Feet Road, Mahesh Nagar, Jaipur, Rajasthan, 302015</td>
        <td>Jaipur</td>
        <td>Rajasthan</td>
        <td>302015</td>
        <!-- <td></td> -->
        <td>9461281557</td>
    </tr>
    <tr class="table-secondary">
        <td>6</td>
        <td>Gaurav Trading Company</td>
        <td>Ikbal Khan</td>
        <td>Gayatri Nagar, Sodala, Ajmer Road, Jaipur, Rajasthan, 302019</td>
        <td>Jaipur</td>
        <td>Rajasthan</td>
        <td>302019</td>
        <!-- <td></td> -->
        <td>9784886107</td>
    </tr>
    <tr class="table-primary">
        <td>7</td>
        <td>Hemank Trading Company</td>
        <td>Kamal Jain</td>
        <td>16, Govindpura, Kardhani, Kalwar Road, Jaipur, Rajasthan, 302012</td>
        <td>Jaipur</td>
        <td>Rajasthan</td>
        <td>302012</td>
        <!-- <td></td> -->
        <td>9694333825</td>
    </tr>
    <tr class="table-secondary">
        <td>8</td>
        <td>Indra Trading Company</td>
        <td>Rajesh Yogi</td>
        <td>Plot No -18,/1, Youjna No-16, Sindhu Nagar, Murlipura, Jaipur, 302039</td>
        <td>Jaipur</td>
        <td>Rajasthan</td>
        <td>302039</td>
        <!-- <td></td> -->
        <td>9782002917</td>
    </tr>
    <tr class="table-primary">
        <td>9</td>
        <td>Indra Trading Company</td>
        <td>Hemraj Meena</td>
        <td>Road No. 9, VKI Area, Jaipur, Jaipur, Rajasthan, 302014</td>
        <td>Jaipur</td>
        <td>Rajasthan</td>
        <td>302014</td>
        <!-- <td></td> -->
        <td>9057174257</td>
    </tr>
    <tr class="table-secondary">
        <td>10</td>
        <td>Oswal Soap Agency</td>
        <td>Sanjay Khan</td>
        <td>CC-5, Jawahar Nagar, Sabji Mandi, Jaipur, Rajasthan, 302004</td>
        <td>Jaipur</td>
        <td>Rajasthan</td>
        <td>302004</td>
        <!-- <td></td> -->
        <td>9828371387</td>
    </tr>
    <tr class="table-primary">
        <td>11</td>
        <td>Sanjay Trading Company</td>
        <td>Deendayal Meena</td>
        <td>30/5/1, Madhyam Marg, Mansarover, Jaipur, Rajasthan, 302020</td>
        <td>Jaipur</td>
        <td>Rajasthan</td>
        <td>302020</td>
        <!-- <td></td> -->
        <td>7891047318</td>
    </tr>
    <tr class="table-secondary">
        <td>12</td>
        <td>Shivali Trading Company</td>
        <td>Firoj Khan</td>
        <td>H-332 D, VKI AREA, Jaipur, Jaipur, Rajasthan, 302013</td>
        <td>Jaipur</td>
        <td>Rajasthan</td>
        <td>302013</td>
        <!-- <td></td> -->
        <td>7357192711</td>
    </tr>
    <tr class="table-primary">
        <td>13</td>
        <td>Saurabh Trading Company</td>
        <td>Omprakash Jayswal</td>
        <td>16, Gokul market, Khatipura Road, Jhotwara, Jaipur, Rajasthan, 302012</td>
        <td>Jaipur</td>
        <td>Rajasthan</td>
        <td>302012</td>
        <!-- <td></td> -->
        <td>9414696896</td>
    </tr>
    <tr class="table-secondary">
        <td>14</td>
        <td>Shrenik Trading Company</td>
        <td>Pankaj Sharma</td>
        <td>11-B, JAI AMBEY NAGAR, TONK ROAD, Jaipur, Rajasthan, 302011</td>
        <td>Jaipur</td>
        <td>Rajasthan</td>
        <td>302011</td>
        <!-- <td></td> -->
        <td>9672642139</td>
    </tr>
    <tr class="table-primary">
        <td>15</td>
        <td>Eight Brothers Sales Pvt. Ltd.</td>
        <td>Jitendra Sharma</td>
        <td>Shop No. 19, L.C. Yogana, Ajmer Road, Bhankrota, Jaipur, Rajasthan, 302026</td>
        <td>Jaipur</td>
        <td>Rajasthan</td>
        <td>302026</td>
        <!-- <td></td> -->
        <td>9782092006</td>
    </tr>
    <tr class="table-secondary">
        <td>16</td>
        <td>Eight Brothers Sales Pvt. Ltd.</td>
        <td>Premprakash Goswami</td>
        <td>Shop No. 21, Sanni Nagar, Pratap Nagar, Housing Board Sanganer, Jaipur, Rajasthan, 302033</td>
        <td>Jaipur</td>
        <td>Rajasthan</td>
        <td>302033</td>
        <!-- <td></td> -->
        <td>9352795425</td>
    </tr>
    <tr class="table-primary">
        <td>17</td>
        <td>Eight Brothers Sales Pvt. Ltd.</td>
        <td>Rajendra Singh</td>
        <td>Shop No. 3, Ganpati Tower, Central Spine, Vidhyadhar Nagar, Jaipur, Rajasthan, 302039</td>
        <td>Jaipur</td>
        <td>Rajasthan</td>
        <td>302039</td>
        <!-- <td></td> -->
        <td>7610048886</td>
    </tr>
    <tr class="table-secondary">
        <td>18</td>
        <td>Eight Brothers Sales Pvt. Ltd.</td>
        <td>Omprakash Vijayvargiya</td>
        <td>23-24, Guruteg Bahadur Nagar, Goner Road, Jaipur, Rajasthan, 303012</td>
        <td>Jaipur</td>
        <td>Rajasthan</td>
        <td>303012</td>
        <!-- <td></td> -->
        <td>9829380340</td>
    </tr>
    <tr class="table-primary">
        <td>19</td>
        <td>Eight Brothers Sales Pvt. Ltd.</td>
        <td>Mahipal Singh Rathore</td>
        <td>Shop No 32-33, Chitrakoot Vistar, Kali Kothi Ke Pass, Chitrakoot Vistar, Niwaru Road, Niwaru, Jaipur, Rajasthan, 302012</td>
        <td>Jaipur</td>
        <td>Rajasthan</td>
        <td>302012</td>
        <!-- <td></td> -->
        <td>7339701176</td>
    </tr>
    <tr class="table-secondary">
        <td>20</td>
        <td>Eight Brothers Sales Pvt. Ltd.</td>
        <td>Satyanaryan Gujar</td>
        <td>Shop No. - 3, Hare Rama Hare Krishna Colony, Village Dholai, Sanganer, Jaipur, Rajasthan, 302029</td>
        <td>Jaipur</td>
        <td>Rajasthan</td>
        <td>302029</td>
        <!-- <td></td> -->
        <td>9116047432</td>
    </tr>
    <tr class="table-primary">
        <td>21</td>
        <td>Eight Brothers Sales Pvt. Ltd.</td>
        <td>Subodh Shrivatsve</td>
        <td>Shop No-13, Ridhi Sidhi Nagar, Gram Muhana, Tehsil Sanganer, Jaipur, Rajasthan, 302033</td>
        <td>Jaipur</td>
        <td>Rajasthan</td>
        <td>302033</td>
        <!-- <td></td> -->
        <td>9748038237</td>
    </tr>
    <tr class="table-secondary">
        <td>22</td>
        <td>Uttam Chand Desraj</td>
        <td></td>
        <td>174/175, Chandpole Bazaar, Jaipur, Jaipur, Rajasthan, 302001</td>
        <td>Jaipur</td>
        <td>Rajasthan</td>
        <td>302001</td>
        <!-- <td></td> -->
        <td>0141-2314857</td>
    </tr>
    <tr class="table-primary">
        <td>23</td>
        <td>Uttam Chand Desraj</td>
        <td>Sunil Sharma</td>
        <td>292, Chandpole Bazaar, Jaipur, Jaipur, Rajasthan, 302001</td>
        <td>Jaipur</td>
        <td>Rajasthan</td>
        <td>302001</td>
        <!-- <td></td> -->
        <td>9460388266</td>
    </tr>
    <tr class="table-secondary">
        <td>24</td>
        <td>Prabhat Trading Company</td>
        <td>Budhiprakash Jain</td>
        <td>Shop No. 6 , 7, Kishan Market, Sanganer, Jaipur, Rajasthan, 302033</td>
        <td>Jaipur</td>
        <td>Rajasthan</td>
        <td>302033</td>
        <!-- <td></td> -->
        <td>8385072727</td>
    </tr>
    <tr class="table-primary">
        <td>25</td>
        <td>Prabhat Trading Company</td>
        <td>Tara Chand Jain</td>
        <td>G-10, Near Bus Stand, Rathi Mension, Ajmer Road, Madanganj, Ajmer, Rajasthan, 305801</td>
        <td>Ajmer</td>
        <td>Rajasthan</td>
        <td>305801</td>
        <!-- <td></td> -->
        <td>7742450825</td>
    </tr>
    <tr class="table-secondary">
        <td>26</td>
        <td>Basant Trading Company</td>
        <td>Ghanshyam Khatter</td>
        <td>GH - 7, Suraj Pole mandi, Jaipur, Jaipur, Rajasthan, 302003</td>
        <td>Jaipur</td>
        <td>Rajasthan</td>
        <td>302003</td>
        <!-- <td></td> -->
        <td>9314090086</td>
    </tr>
    <tr class="table-primary">
        <td>27</td>
        <td>Basant Trading Company</td>
        <td>Parlahad Parhmanik</td>
        <td>Shop No. 51, Kayasth Market, M G Hospital Road, Jodhpur, Jodhpur, Rajasthan, 342001</td>
        <td>Jodhpur</td>
        <td>Rajasthan</td>
        <td>342001</td>
        <!-- <td></td> -->
        <td>8279255965</td>
    </tr>
    <tr class="table-secondary">
        <td>28</td>
        <td>Basant Trading Company</td>
        <td>Shivprakash Agarwal</td>
        <td>Shop No 4, Opp Town Police Chowki, Railway Station Road, Makrana, Nagaur, Rajasthan, 341505</td>
        <td>Makrana</td>
        <td>Rajasthan</td>
        <td>341505</td>
        <!-- <td></td> -->
        <td>9785844598</td>
    </tr>
    <tr class="table-primary">
        <td>29</td>
        <td>Basant Trading Company</td>
        <td>Murari Lal Meena</td>
        <td>Near Sales Tax Office, Bundi Deoli Bye Pass Road, Bundi, Bundi, Rajasthan, 323001</td>
        <td>Bundi</td>
        <td>Rajasthan</td>
        <td>323001</td>
        <!-- <td></td> -->
        <td>9214306974</td>
    </tr>
</tbody>

          
        </table>
        </div>
    </div>
</div>


<!-- Bootstrap JS (Optional for full functionality) -->

    @endsection
    
