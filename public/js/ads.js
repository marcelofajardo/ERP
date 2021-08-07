var campainingType = [
    {
        name    : 'Search', 
        parent  : ['Sales','Leads','Web traffic',"Create a campaign without a goal's guidance"]
    },
    {
        name    : 'Display',
        parent  : ['Sales','Leads','Web traffic','Brand awareness and reach',"Create a campaign without a goal's guidance",'Product and brand consideration']
    },
    {
        name    : 'Shopping',
        parent  : ['Sales','Leads','Web traffic',"Create a campaign without a goal's guidance"]
    },
    {
        name    : 'Video',
        parent  : ['Sales','Leads','Web traffic','Product and brand consideration','Brand awareness and reach',"Create a campaign without a goal's guidance"]
    },
    {
        name    : 'App',
        parent  : ['App promotion',"Create a campaign without a goal's guidance"]
    },
    {
        name    : 'Smart',
        parent  : ['Sales','Leads',"Create a campaign without a goal's guidance"]
    },
    {
        name    : 'Local',
        parent  : ['Local store visits and promotions',"Create a campaign without a goal's guidance"]
    },
    {
        name    : 'Discovery',
        parent  : ['Sales','Leads','Web traffic',"Create a campaign without a goal's guidance"]
    }
];
var thirtChild = [
                    {
                        goal    : 'Sales',
                        parent  : 'Search',
                        html    : `<div class="form-group row campanin-type-child child-campaning-goal">
                                        <label for="status" class="col-sm-12 col-form-label">Select the ways you'd like to reach your goal</label>
                                        <div class="col-sm-12">
                                            <div class="row">
                                                <div class="col-md-1">
                                                    <input type="checkbox" value="Website visits" name="data[reach_goal][website]" id="wbsitevisit">
                                                </div>
                                                <div class="col-md-11">
                                                    <label>Website visits</lable>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-1">
                                                    <input type="checkbox" value="Phone calls" id="phonecalls">
                                                </div>
                                                <div class="col-md-11">
                                                    <label>Phone calls</lable>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-1">
                                                    <input type="checkbox" value="Store visits">
                                                </div>
                                                <div class="col-md-11">
                                                    <label>Store visits</lable>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-1">
                                                    <input type="checkbox" value="App downloads" id="appdownload">
                                                </div>
                                                <div class="col-md-11">
                                                    <label>App downloads</lable>
                                                </div>
                                            </div>
                                        </div>
                                    </div>`,
                    },
                    {
                        goal    : 'Sales',
                        parent  : 'Display',
                        html    : `<div class="form-group row campanin-type-child child-campaning-goal">
                                        <label for="status" class="col-sm-12 col-form-label">Select a campaign subtype. Keep in mind that this selection can’t be changed later.</label>
                                        <div class="col-sm-12">
                                            <div class="row">
                                                <div class="col-md-3">
                                                    <input type="radio" name="campaning_subtype" value="Smart display campaign" class="form-control" checked>
                                                </div>
                                                <div class="col-md-6">
                                                    <label>Smart display campaign</lable>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-3">
                                                    <input type="radio" name="campaning_subtype" value="Standard display campaign" class="form-control">
                                                </div>
                                                <div class="col-md-6">
                                                    <label>Standard display campaign</lable>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-3">
                                                    <input type="radio" name="campaning_subtype" value="Gmail campaign" class="form-control">
                                                </div>
                                                <div class="col-md-6">
                                                    <label>Gmail campaign</lable>
                                                </div>
                                            </div>
                                        </div>
                                        <label for="status" class="col-sm-12 col-form-label">Select the ways you'd like to reach your goal</label>
                                        <div class="col-md-12">
                                            <input type="url" name="bussinessurrl" class="form-control">
                                        </div>
                                    </div>`,
                    },
                    {
                        goal    : 'Sales',
                        parent  : 'Shopping',
                        html    : `<div class="form-group row campanin-type-child child-campaning-goal">
                                        <label for="status" class="col-sm-12 col-form-label">Select a linked account with products to advertise in this campaign </label>
                                        <div class="col-sm-12">
                                            <input type="text" name="linkedacc" class="form-control">
                                        </div>
                                        <label for="status" class="col-sm-12 col-form-label">Select the country where products are sold</label>
                                        <div class="col-md-12">
                                            <input type="text" name="country" class="form-control">
                                        </div>
                                    </div>`,
                    },
                    {
                        goal    : 'Leads',
                        parent  : 'Search',
                        html    : `<div class="form-group row campanin-type-child child-campaning-goal">
                                        <label for="status" class="col-sm-12 col-form-label">Select the ways you'd like to reach your goal</label>
                                        <div class="col-sm-12">
                                            <div class="row">
                                                <div class="col-md-3">
                                                    <input type="checkbox" value="Website visits" id="wbsitevisit" class="form-control">
                                                </div>
                                                <div class="col-md-3">
                                                    <label>Website visits</lable>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-3">
                                                    <input type="checkbox" value="Phone calls" id="phonecalls" class="form-control">
                                                </div>
                                                <div class="col-md-3">
                                                    <label>Phone calls</lable>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-3">
                                                    <input type="checkbox" value="Store visits" class="form-control">
                                                </div>
                                                <div class="col-md-9">
                                                    <label>Store visits</lable>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-3">
                                                    <input type="checkbox" value="App downloads" id="appdownload" class="form-control">
                                                </div>
                                                <div class="col-md-9">
                                                    <label>App downloads</lable>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-3">
                                                    <input type="checkbox" value="Lead form submissions" class="form-control">
                                                </div>
                                                <div class="col-md-9">
                                                    <label>Lead form submissions</lable>
                                                </div>
                                            </div>
                                        </div>
                                    </div>`,
                    },
                    {
                        goal    : 'Leads',
                        parent  : 'Display',
                        html    : `<div class="form-group row campanin-type-child child-campaning-goal">
                                        <label for="status" class="col-sm-12 col-form-label">Select a campaign subtype. Keep in mind that this selection can’t be changed later.</label>
                                        <div class="col-sm-12">
                                            <div class="row">
                                                <div class="col-md-3">
                                                    <input type="radio" name="campaning_subtype" value="Smart display campaign" class="form-control" checked>
                                                </div>
                                                <div class="col-md-6">
                                                    <label>Smart display campaign</lable>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-3">
                                                    <input type="radio" name="campaning_subtype" value="Standard display campaign" class="form-control">
                                                </div>
                                                <div class="col-md-6">
                                                    <label>Standard display campaign</lable>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-3">
                                                    <input type="radio" name="campaning_subtype" value="Gmail campaign" class="form-control">
                                                </div>
                                                <div class="col-md-6">
                                                    <label>Gmail campaign</lable>
                                                </div>
                                            </div>
                                        </div>
                                        <label for="status" class="col-sm-12 col-form-label">Select the ways you'd like to reach your goal</label>
                                        <div class="col-md-12">
                                            <input type="url" name="bussinessurrl" class="form-control">
                                        </div>
                                    </div>`,
                    },
                    {
                        goal    : 'Leads',
                        parent  : 'Shopping',
                        html    : `<div class="form-group row campanin-type-child child-campaning-goal">
                                        <label for="status" class="col-sm-12 col-form-label">Select a linked account with products to advertise in this campaign </label>
                                        <div class="col-sm-12">
                                            <input type="text" name="linkedacc" class="form-control">
                                        </div>
                                        <label for="status" class="col-sm-12 col-form-label">Select the country where products are sold</label>
                                        <div class="col-md-12">
                                            <input type="text" name="country" class="form-control">
                                        </div>
                                    </div>`,
                    },
                    {
                        goal    : 'Leads',
                        parent  : 'Smart',
                        html    : `<div class="form-group row campanin-type-child child-campaning-goal">
                                        <label for="status" class="col-sm-12 col-form-label">What action do you most want customers to take?</label>
                                        <div class="col-sm-12">
                                            <div class="row">
                                                <div class="col-md-3">
                                                    <input type="radio" name="campaning_subtype" value="Calls to your business" class="form-control" checked>
                                                </div>
                                                <div class="col-md-6">
                                                    <label>Calls to your business</lable>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-3">
                                                    <input type="radio" name="campaning_subtype" value="Visits to your storefront" class="form-control">
                                                </div>
                                                <div class="col-md-6">
                                                    <label>Visits to your storefront</lable>
                                                </div>
                                            </div>
                                        </div>
                                    </div>`,
                    },
                    {
                        goal    : 'Web traffic',
                        parent  : 'Search',
                        html    : `<div class="form-group row campanin-type-child child-campaning-goal">
                                        <label for="status" class="col-sm-12 col-form-label">Select the ways you'd like to reach your goal</label>
                                        <div class="col-md-12">
                                            <input type="url" name="bussinessurrl" class="form-control">
                                        </div>
                                    </div>`,
                    },
                    {
                        goal    : 'Web traffic',
                        parent  : 'Display',
                        html    : `<div class="form-group row campanin-type-child child-campaning-goal">
                                        <label for="status" class="col-sm-12 col-form-label">Select a campaign subtype. Keep in mind that this selection can’t be changed later.</label>
                                        <div class="col-sm-12">
                                            <div class="row">
                                                <div class="col-md-3">
                                                    <input type="radio" name="campaning_subtype" value="Smart display campaign" class="form-control" checked>
                                                </div>
                                                <div class="col-md-6">
                                                    <label>Smart display campaign</lable>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-3">
                                                    <input type="radio" name="campaning_subtype" value="Standard display campaign" class="form-control">
                                                </div>
                                                <div class="col-md-6">
                                                    <label>Standard display campaign</lable>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-3">
                                                    <input type="radio" name="campaning_subtype" value="Gmail campaign" class="form-control">
                                                </div>
                                                <div class="col-md-6">
                                                    <label>Gmail campaign</lable>
                                                </div>
                                            </div>
                                        </div>
                                        <label for="status" class="col-sm-12 col-form-label">Select the ways you'd like to reach your goal</label>
                                        <div class="col-md-12">
                                            <input type="url" name="bussinessurrl" class="form-control">
                                        </div>
                                    </div>`,
                    },
                    {
                        goal    : 'Web traffic',
                        parent  : 'Shopping',
                        html    : `<div class="form-group row campanin-type-child child-campaning-goal">
                                        <label for="status" class="col-sm-12 col-form-label">Select a linked account with products to advertise in this campaign </label>
                                        <div class="col-sm-12">
                                            <input type="text" name="linkedacc" class="form-control">
                                        </div>
                                        <label for="status" class="col-sm-12 col-form-label">Select the country where products are sold</label>
                                        <div class="col-md-12">
                                            <input type="text" name="country" class="form-control">
                                        </div>
                                    </div>`,
                    },
                    {
                        goal    : 'Product and brand consideration',
                        parent  : 'Display',
                        html    : `<div class="form-group row campanin-type-child child-campaning-goal">
                                        <label for="status" class="col-sm-12 col-form-label">Select the ways you'd like to reach your goal</label>
                                        <div class="col-md-12">
                                            <input type="url" name="bussinessurrl" class="form-control">
                                        </div>
                                    </div>`,
                    },
                    {
                        goal    : 'Product and brand consideration',
                        parent  : 'Video',
                        html    : `<div class="form-group row campanin-type-child child-campaning-goal">
                                        <label for="status" class="col-sm-12 col-form-label">Select a campaign subtype</label>
                                        <div class="col-sm-12">
                                            <div class="row">
                                                <div class="col-md-3">
                                                    <input type="radio" name="campaning_subtype" value="Influence consideration" class="form-control" checked>
                                                </div>
                                                <div class="col-md-6">
                                                    <label>Influence consideration</lable>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-3">
                                                    <input type="radio" name="campaning_subtype" value="Ad sequence" class="form-control">
                                                </div>
                                                <div class="col-md-6">
                                                    <label>Ad sequence</lable>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-3">
                                                    <input type="radio" name="campaning_subtype" value="Shopping" class="form-control">
                                                </div>
                                                <div class="col-md-6">
                                                    <label>Shopping</lable>
                                                </div>
                                            </div>
                                        </div>
                                    </div>`,
                    },
                    {
                        goal    : 'Brand awareness and reach',
                        parent  : 'Display',
                        html    : `<div class="form-group row campanin-type-child child-campaning-goal">
                                        <label for="status" class="col-sm-12 col-form-label">Select the ways you'd like to reach your goal</label>
                                        <div class="col-md-12">
                                            <input type="url" name="bussinessurrl" class="form-control">
                                        </div>
                                    </div>`,
                    },
                    {
                        goal    : 'Brand awareness and reach',
                        parent  : 'Video',
                        html    : `<div class="form-group row campanin-type-child child-campaning-goal">
                                        <label for="status" class="col-sm-12 col-form-label">Select a campaign subtype</label>
                                        <div class="col-sm-12">
                                            <div class="row">
                                                <div class="col-md-3">
                                                    <input type="radio" name="campaning_subtype" value="Skippable in-stream" class="form-control" checked>
                                                </div>
                                                <div class="col-md-6">
                                                    <label>Skippable in-stream</lable>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-3">
                                                    <input type="radio" name="campaning_subtype" value="Bumper" class="form-control">
                                                </div>
                                                <div class="col-md-6">
                                                    <label>Bumper</lable>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-3">
                                                    <input type="radio" name="campaning_subtype" value="Non-skippable in-stream" class="form-control">
                                                </div>
                                                <div class="col-md-6">
                                                    <label>Non-skippable in-stream</lable>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-3">
                                                    <input type="radio" name="campaning_subtype" value="Outstream" class="form-control">
                                                </div>
                                                <div class="col-md-6">
                                                    <label>Outstream</lable>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-3">
                                                    <input type="radio" name="campaning_subtype" value="Ad sequence" class="form-control">
                                                </div>
                                                <div class="col-md-6">
                                                    <label>Ad sequence</lable>
                                                </div>
                                            </div>
                                        </div>
                                    </div>`,
                    },
                    {
                        goal    : 'App promotion',
                        parent  : 'App',
                        html    : `<div class="form-group row campanin-type-child child-campaning-goal">
                                        <label for="status" class="col-sm-12 col-form-label">Select a campaign subtype</label>
                                        <div class="col-sm-12">
                                            <div class="row">
                                                <div class="col-md-3">
                                                    <input type="radio" name="campaning_subtype" value="App installs" class="form-control" checked>
                                                </div>
                                                <div class="col-md-6">
                                                    <label>App installs</lable>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-3">
                                                    <input type="radio" name="campaning_subtype" value="App engagement" class="form-control">
                                                </div>
                                                <div class="col-md-6">
                                                    <label>App engagement</lable>
                                                </div>
                                            </div>
                                        </div>
                                        <label for="status" class="col-sm-12 col-form-label">Select your mobile app's platform</label>
                                        <div class="col-sm-12">
                                            <div class="row">
                                                <div class="col-md-3">
                                                    <input type="radio" name="campaning_subtype" value="Android" class="form-control" checked>
                                                </div>
                                                <div class="col-md-6">
                                                    <label>Android</lable>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-3">
                                                    <input type="radio" name="campaning_subtype" value="iOS" class="form-control">
                                                </div>
                                                <div class="col-md-6">
                                                    <label>iOS</lable>
                                                </div>
                                            </div>
                                        </div>
                                        <label for="status" class="col-sm-12 col-form-label">Select the ways you'd like to reach your goal</label>
                                        <div class="col-md-12">
                                            <input type="url" name="bussinessurrl" placeholder="Enter the app name,package name, or publisher" class="form-control">
                                        </div>
                                    </div>`,
                    },
                    {
                        goal    : 'Local store visits and promotions',
                        parent  : 'Local',
                        html    : `<div class="form-group row campanin-type-child child-campaning-goal">
                                        <label for="status" class="col-sm-12 col-form-label">Select the type of locations you want to advertise in this campaign</label>
                                        <div class="col-sm-12">
                                            <div class="row">
                                                <div class="col-md-3">
                                                    <input type="radio" name="campaning_subtype" value="Use Google My Business locations" class="form-control" checked>
                                                </div>
                                                <div class="col-md-6">
                                                    <label>Use Google My Business locations</lable>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-3">
                                                    <input type="radio" name="campaning_subtype" value="Use affiliate locations" class="form-control">
                                                </div>
                                                <div class="col-md-6">
                                                    <label>Use affiliate locations</lable>
                                                </div>
                                            </div>
                                        </div>
                                    </div>`,
                    },
                    {
                        goal    : "Create a campaign without a goal's guidance",
                        parent  : 'Search',
                        html    : `<div class="form-group row campanin-type-child child-campaning-goal">
                                        <label for="status" class="col-sm-12 col-form-label">Select the ways you'd like to reach your goal</label>
                                        <div class="col-sm-12">
                                            <div class="row">
                                                <div class="col-md-3">
                                                    <input type="checkbox" value="Website visits" id="wbsitevisit" class="form-control">
                                                </div>
                                                <div class="col-md-3">
                                                    <label>Website visits</lable>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-3">
                                                    <input type="checkbox" value="Phone calls" id="phonecalls" class="form-control">
                                                </div>
                                                <div class="col-md-3">
                                                    <label>Phone calls</lable>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-3">
                                                    <input type="checkbox" value="App downloads" id="appdownload" class="form-control">
                                                </div>
                                                <div class="col-md-9">
                                                    <label>App downloads</lable>
                                                </div>
                                            </div>
                                        </div>
                                    </div>`,
                    },
                    {
                        goal    : "Create a campaign without a goal's guidance",
                        parent  : 'Display',
                        html    : `<div class="form-group row campanin-type-child child-campaning-goal">
                                        <label for="status" class="col-sm-12 col-form-label">Select a campaign subtype. Keep in mind that this selection can’t be changed later.</label>
                                        <div class="col-sm-12">
                                            <div class="row">
                                                <div class="col-md-3">
                                                    <input type="radio" name="campaning_subtype" value="Smart display campaign" class="form-control" checked>
                                                </div>
                                                <div class="col-md-6">
                                                    <label>Smart display campaign</lable>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-3">
                                                    <input type="radio" name="campaning_subtype" value="Standard display campaign" class="form-control">
                                                </div>
                                                <div class="col-md-6">
                                                    <label>Standard display campaign</lable>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-3">
                                                    <input type="radio" name="campaning_subtype" value="Gmail campaign" class="form-control">
                                                </div>
                                                <div class="col-md-6">
                                                    <label>Gmail campaign</lable>
                                                </div>
                                            </div>
                                        </div>
                                        <label for="status" class="col-sm-12 col-form-label">Select the ways you'd like to reach your goal</label>
                                        <div class="col-md-12">
                                            <input type="url" name="bussinessurrl" class="form-control">
                                        </div>
                                    </div>`,
                    },
                    {
                        goal    : "Create a campaign without a goal's guidance",
                        parent  : 'Shopping',
                        html    : `<div class="form-group row campanin-type-child child-campaning-goal">
                                        <label for="status" class="col-sm-12 col-form-label">Select a linked account with products to advertise in this campaign </label>
                                        <div class="col-sm-12">
                                            <input type="text" name="linkedacc" class="form-control">
                                        </div>
                                        <label for="status" class="col-sm-12 col-form-label">Select the country where products are sold</label>
                                        <div class="col-md-12">
                                            <input type="text" name="country" class="form-control">
                                        </div>
                                    </div>`,
                    },
                    {
                        goal    : "Create a campaign without a goal's guidance",
                        parent  : 'Video',
                        html    : `<div class="form-group row campanin-type-child child-campaning-goal">
                                        <label for="status" class="col-sm-12 col-form-label">Select a campaign subtype</label>
                                        <div class="col-sm-12">
                                            <div class="row">
                                                <div class="col-md-3">
                                                    <input type="radio" name="campaning_subtype" value="Skippable in-stream" class="form-control" checked>
                                                </div>
                                                <div class="col-md-6">
                                                    <label>Skippable in-stream</lable>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-3">
                                                    <input type="radio" name="campaning_subtype" value="Bumper" class="form-control">
                                                </div>
                                                <div class="col-md-6">
                                                    <label>Bumper</lable>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-3">
                                                    <input type="radio" name="campaning_subtype" value="Non-skippable in-stream" class="form-control">
                                                </div>
                                                <div class="col-md-6">
                                                    <label>Non-skippable in-stream</lable>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-3">
                                                    <input type="radio" name="campaning_subtype" value="Outstream" class="form-control">
                                                </div>
                                                <div class="col-md-6">
                                                    <label>Outstream</lable>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-3">
                                                    <input type="radio" name="campaning_subtype" value="Ad sequence" class="form-control">
                                                </div>
                                                <div class="col-md-6">
                                                    <label>Ad sequence</lable>
                                                </div>
                                            </div>
                                        </div>
                                    </div>`,
                    },
                    {
                        goal    : "Create a campaign without a goal's guidance",
                        parent  : 'App',
                        html    : `<div class="form-group row campanin-type-child child-campaning-goal">
                                        <label for="status" class="col-sm-12 col-form-label">Select a campaign subtype</label>
                                        <div class="col-sm-12">
                                            <div class="row">
                                                <div class="col-md-3">
                                                    <input type="radio" name="campaning_subtype" value="App installs" class="form-control" checked>
                                                </div>
                                                <div class="col-md-6">
                                                    <label>App installs</lable>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-3">
                                                    <input type="radio" name="campaning_subtype" value="App engagement" class="form-control">
                                                </div>
                                                <div class="col-md-6">
                                                    <label>App engagement</lable>
                                                </div>
                                            </div>
                                        </div>
                                        <label for="status" class="col-sm-12 col-form-label">Select your mobile app's platform</label>
                                        <div class="col-sm-12">
                                            <div class="row">
                                                <div class="col-md-3">
                                                    <input type="radio" name="campaning_subtype" value="Android" class="form-control" checked>
                                                </div>
                                                <div class="col-md-6">
                                                    <label>Android</lable>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-3">
                                                    <input type="radio" name="campaning_subtype" value="iOS" class="form-control">
                                                </div>
                                                <div class="col-md-6">
                                                    <label>iOS</lable>
                                                </div>
                                            </div>
                                        </div>
                                        <label for="status" class="col-sm-12 col-form-label">Select the ways you'd like to reach your goal</label>
                                        <div class="col-md-12">
                                            <input type="url" name="bussinessurrl" placeholder="Enter the app name,package name, or publisher" class="form-control">
                                        </div>
                                    </div>`,
                    },
                    {
                        goal    : "Create a campaign without a goal's guidance",
                        parent  : 'Smart',
                        html    : `<div class="form-group row campanin-type-child child-campaning-goal">
                                        <label for="status" class="col-sm-12 col-form-label">What action do you most want customers to take?</label>
                                        <div class="col-sm-12">
                                            <div class="row">
                                                <div class="col-md-3">
                                                    <input type="radio" name="campaning_subtype" value="Calls to your business" class="form-control" checked>
                                                </div>
                                                <div class="col-md-6">
                                                    <label>Calls to your business</lable>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-3">
                                                    <input type="radio" name="campaning_subtype" value="Visits to your storefront" class="form-control">
                                                </div>
                                                <div class="col-md-6">
                                                    <label>Visits to your storefront</lable>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-3">
                                                    <input type="radio" name="campaning_subtype" value="Actions on your website" class="form-control">
                                                </div>
                                                <div class="col-md-6">
                                                    <label>Actions on your website</lable>
                                                </div>
                                            </div>
                                        </div>
                                    </div>`,
                    },
                    {
                        goal    : "Create a campaign without a goal's guidance",
                        parent  : 'Local',
                        html    : `<div class="form-group row campanin-type-child child-campaning-goal">
                                        <label for="status" class="col-sm-12 col-form-label">Select the type of locations you want to advertise in this campaign</label>
                                        <div class="col-sm-12">
                                            <div class="row">
                                                <div class="col-md-3">
                                                    <input type="radio" name="campaning_subtype" value="Use Google My Business locations" class="form-control" checked>
                                                </div>
                                                <div class="col-md-6">
                                                    <label>Use Google My Business locations</lable>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-3">
                                                    <input type="radio" name="campaning_subtype" value="Use affiliate locations" class="form-control">
                                                </div>
                                                <div class="col-md-6">
                                                    <label>Use affiliate locations</lable>
                                                </div>
                                            </div>
                                        </div>
                                    </div>`,
                    },
                ]
$(document).on('change','#goal',function(){
    $('.child-campaning-goal').remove();
    let goal = $(this).val();
    let html = `<div class="form-group child-campaning-goal row" >
                        <div class="col-sm-12">
                            <span for="campanin-type">Select the type</span>
                            <select class="browser-default custom-select" id="campanin-type" name="type" style="height: auto" required="">
                             <option value="" selected>-----Select type-----</option>
                           `;
    if (goal != ''){
        $.each(campainingType,function(key,value){
            if (value.parent.indexOf(goal) != -1){
                html += `<option value="${value.name}">${value.name}</option>`;
            }
        });
        html += `   </select>
                </div>
            </div>`;
        $('.create-campaning').append(html);
    }
});
$(document).on('change','#campanin-type',function(){
    $('.campanin-type-child').remove();
    let goal = $('#goal').val();
    let type = $(this).val();
    $.each(thirtChild,function(key,value){
        if (value.goal == goal && value.parent == type){
            $('.create-campaning').append(value.html);
        }
    });
});
$(document).on('change','#wbsitevisit',function(){
    $('.wbsitevisitcheck').remove();
    if ($(this).is(':checked')){
        $(this).parent().parent().append('<div class="col-md-6 wbsitevisitcheck"> <input type="url" class="form-control " name="data[reach_goal][website][input]" placeholder="Your bussiness url"/> </div>');
    }
});
$(document).on('change','#phonecalls',function(){
    $('.phonecallscheck').remove();
    if ($(this).is(':checked')){
        $(this).parent().parent().append('<div class="col-md-3 phonecallscheck"> <select name="countrycode" class="form-control"><option value="IN">IN</option></select> </div><div class="col-md-3 phonecallscheck"><input type="url" class="form-control " name="phonecall" placeholder="Phone number"/> </div>');
    }
});
$(document).on('change','#appdownload',function(){
    $('.appdownloadcheck').remove();
    if ($(this).is(':checked')){
        $(this).parent().parent().after(`<div class="row appdownloadcheck">
                                                        <label for="status" class="col-sm-12 col-form-label">Select your mobile app's platform</label>
                                                        <div class="col-md-3">
                                                            <input type="radio" name="app_platform" value="Android" class="form-control" checked />
                                                        </div>
                                                        <div class="col-md-3">
                                                            <label>Android</lable>
                                                        </div>
                                                    </div>
                                                    <div class="row appdownloadcheck">
                                                        <div class="col-md-3">
                                                            <input type="radio" name="app_platform" value="IOS" class="form-control" />
                                                        </div>
                                                        <div class="col-md-3">
                                                            <label>IOS</lable>
                                                        </div>
                                                    </div>
                                                    <div class="row appdownloadcheck">
                                                        <div class="col-md-12">
                                                            <input type="text" class="form-control" placeholder="Enter the app name,package name,or publisher"/>
                                                        </div>
                                                    </div>`);
    }
});
var customParamsCount = 1;
$(document).on('click','.addurlcustomvalue',function(){
    $(this).before(`<div class="form-group row">
                        <div class="col-md-4">
                            <input type="text"  name="data[campaign_url][custom_param][${customParamsCount}][name]" class="form-control" placeholder="Name">
                        </div>
                        <div class="col-md-1">
                            =
                        </div>
                        <div class="col">
                            <input type="text"  name="data[campaign_url][custom_param][${customParamsCount}][value]" class="form-control" placeholder="Value">
                        </div>
                    </div>`);
    customParamsCount++
});
let scheduleCount = 1;
$(document).on('click','.addSchedule',function(){
    $(this).before(`<div class="form-group row">
                        <div class="col-md-5">
                            <select  class="form-control" name="data[ads_schedule][${scheduleCount}][day]">
                                <option value="All days">All days</option>
                                <option value="Mondays-Friday">Mondays-Friday</option>
                                <option value="Saturdays-Sundays">Saturdays-Sundays</option>
                                <option value="Mondays">Mondays</option>
                                <option value="Tuesdays">Tuesdays</option>
                                <option value="Wednesdays">Wednesdays</option>
                                <option value="Thursdays">Thursdays</option>
                                <option value="Fridays">Fridays</option>
                                <option value="Saturdays">Saturdays</option>
                                <option value="Sundays">Sundays</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <input type="text" name="data[ads_schedule][${scheduleCount}][from]" class="form-control" value="00:00">
                        </div>
                        <div class="col-md-1">
                            to
                        </div>
                        <div class="col-md-3">
                            <input type="text" name="data[ads_schedule][${scheduleCount}][to]" class="form-control" value="00:00">
                        </div>
                    </div>`);
    scheduleCount++
});
$(document).on('click','#continue-phase-1',function(){
    // $('.create-campaning').hide();
    // $('.create-campaning-phase-2').show();
    // $('#continue-phase-1').hide();
    // $('#create-campaign-btn').show();
    // initPhase2();
});

$(document).on('submit','#create-ad-campaign-form',function(e){
    var status = $('#addAccountStatus').val();
    if(status == 1) {
        e.preventDefault();
    }
    if($("#create-ad-campaign-form").valid()) {
        if(status == 1) {
            $('.create-campaning').hide();
            $('.create-campaning-phase-2').show();
            $('#continue-phase-1').hide();
            $('#create-campaign-btn').show();
            initPhase2();
        }
    }
});


function initPhase2(){
    $html = `<label class="col-sm-6 col-form-label">Type: <span id="phase-1-type"></span></label>
                    <label class="col-sm-6 col-form-label">Goal: <span id="phase-1-goal"></span></label>
                    <div class="row">
                        <div class="form-group col-sm-12">
                            <label for="status" class="col-sm-12 col-form-label">Campaign name</label>
                            <div class="col-md-12">
                                <input type="text" class="form-control" name="camp_name" placeholder="Enter a campaign name" required=""/>
                            </div>
                        </div> 
                    </div>      
            <div class="row campanin-type-child child-campaning-goal">
                <div class="col-sm-4">
                    <label for="status" class="col-sm-12 col-form-label">Networks</label>
                </div>
                <div class="col-sm-8">
                    <div class="row">
                        <div class="col-md-12">
                            <label>Search Network</lable>
                            <p style="font-weight: 200;">Ads can appear near Google Search results and other Google sites when people search for terms that are relevant to your keywords</p>
                        </div>
                        <div class="col-md-1">
                            <input type="checkbox" name="data[network][search_network]" value="Include Google search partners">
                        </div>
                        <div class="col-md-6">
                            <label>Include Google search partners</lable>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <br>
                            <label>Display Network</lable>
                            <p style="font-weight: 200;">Expand your reach by showing ads to relevant customers as they browse sites, videos, and apps across the Internet</p>
                        </div>
                        <div class="col-md-1">
                            <input type="checkbox" name="data[network][display_network]" value="Include Google search partners">
                        </div>
                        <div class="col-md-6">
                            <label>Include Google search partners</lable>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row campanin-type-child child-campaning-goal">
                <div class="col-md-12">
                    <a href="#demo" class="col-sm-12" data-toggle="collapse">Show more settings</a>
                    <div id="demo" class="col-sm-12 collapse">
                        <div class="form-group row">
                            <div class="col-md-4">
                                <label>Start and end dates</lable>
                            </div>
                            <div class="form-group col-md-6">
                                <p>Start date</p>
                                <input type="date" name="data[start_end_dated][startdate]" class="form-control">
                            </div>
                            <div class="col-md-4">
                                
                            </div>
                            <div class="col-md-6">
                                <p>End date</p>
                                <div class="row">
                                    <div class="col-md-1">
                                        <input type="radio" name="data[start_end_dated][type]" value="none" checked>
                                    </div>
                                    <div class="col-md-6">
                                        <label>None</lable>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-1">
                                        <input type="radio" name="data[start_end_dated][type]" value="date">
                                    </div>
                                    <div class="col-md-6">
                                        <input type="date" name="data[start_end_dated][enddate]" class="form-control">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <label>Campaign URL options</lable>
                            </div>
                            <div class="col-md-8">
                                <div class="form-group row">
                                    <div class="form-group col-md-10">
                                        <p>Tracking template</p>
                                        <input type="text"  name="data[campaign_url][tracking_tamplate]" class="form-control">
                                    </div>
                                    <div class="form-group col-md-10">
                                        <p>Final URL Suffix</p>
                                        <input type="text"  name="data[campaign_url][final_url_suffix]" class="form-control">
                                    </div>
                                    <div class="form-group col-md-10">
                                        <p>Custom parameters</p>
                                        <div class="form-group row">
                                            <div class="col-md-4">
                                                <input type="text"  name="data[campaign_url][custom_param][0][name]" class="form-control" placeholder="Name">
                                            </div>
                                            <div class="col-md-1">
                                                =
                                            </div>
                                            <div class="col">
                                                <input type="text"  name="data[campaign_url][custom_param][0][value]" class="form-control" placeholder="Value">
                                            </div>
                                        </div>
                                        <button class="btn btn-primary pull-right addurlcustomvalue" type="button">+</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-4">
                                <label>Dynamic Search Ads setting</lable>
                            </div>
                            <div class="form-group col-md-6">
                                <p>Enter the domain</p>
                                <input type="text" name="data[dynamic_search_ads_setting][domain]" class="form-control">
                            </div>
                            <div class="col-md-4">
                                
                            </div>
                            <div class="form-group col-md-6">
                                <p>Select the language of the Dynamic Search Ads within this campaign</p>
                                <select  class="form-control" name="data[dynamic_search_ads_setting][language]">
                                    <option value="English">English</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                
                            </div>
                            <div class="form-group col-md-6">
                                <p>Select a targeting source:</p>
                                <div class="row">
                                    <div class="col-md-1">
                                        <input type="radio" name="data[dynamic_search_ads_setting][target_source]" value="Use Google's index of my website" checked>
                                    </div>
                                    <div class="col-md-11">
                                        <label>Use Google's index of my website</lable>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-1">
                                        <input type="radio" name="data[dynamic_search_ads_setting][target_source]" value="Use URLs from my page feed only">
                                    </div>
                                    <div class="col-md-11">
                                        <label>Use URLs from my page feed only</lable>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-1">
                                        <input type="radio" name="data[dynamic_search_ads_setting][target_source]" value="Actions on your website">
                                    </div>
                                    <div class="col-md-11">
                                        <label>Use URLs from both Google's index of my website and my page feed</lable>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <label>Ad schedule</lable>
                            </div>
                            <div class="col-md-8">
                                <div class="form-group row">
                                    <div class="col-md-5">
                                        <select  class="form-control" name="data[ads_schedule][0][day]">
                                            <option value="All days">All days</option>
                                            <option value="Mondays-Friday">Mondays-Friday</option>
                                            <option value="Saturdays-Sundays">Saturdays-Sundays</option>
                                            <option value="Mondays">Mondays</option>
                                            <option value="Tuesdays">Tuesdays</option>
                                            <option value="Wednesdays">Wednesdays</option>
                                            <option value="Thursdays">Thursdays</option>
                                            <option value="Fridays">Fridays</option>
                                            <option value="Saturdays">Saturdays</option>
                                            <option value="Sundays">Sundays</option>
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <input type="text" name="data[ads_schedule][0][from]" class="form-control" value="00:00">
                                    </div>
                                    <div class="col-md-1">
                                        to
                                    </div>
                                    <div class="col-md-3">
                                        <input type="text" name="data[ads_schedule][0][to]" class="form-control" value="00:00">
                                    </div>
                                </div>
                                <button class="btn btn-primary pull-right addSchedule" type="button">add</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row campanin-type-child child-campaning-goal">
                <div class="col-md-12">
                    <h3 class="col-sm-12">Targeting and audiences</h3>
                    <p class="col-sm-12">Choose who you want to reach</p>
                </div>
                <div class="col-md-12">
                    <div class="">
                        <div class="col-md-4">
                            <br>
                            <br>
                            <label>Locations</lable>
                        </div>
                        <div class="col-md-6">
                            <p>Select locations to target</p>
                            <div class="row">
                                <div class="col-md-1">
                                    <input type="radio" name="data[targeting_and_audience][location][location]" value="All countries and territories" class="location_radio" checked>
                                </div>
                                <div class="col-md-11">
                                    <label>All countries and territories</lable>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-1">
                                    <input type="radio" name="data[targeting_and_audience][location][location]" value="India" class="location_radio">
                                </div>
                                <div class="col-md-6">
                                    <label>India</lable>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-1">
                                    <input type="radio" name="data[targeting_and_audience][location][location]" value="Enter another location" class="location_radio">
                                </div>
                                <div class="col-md-6">
                                    <label>Enter another location</lable>
                                </div>
                            </div>
                            <a href="#demo1" data-toggle="collapse">Location Option</a>
                            <div id="demo1" class="collapse">
                                <div class="row">
                                    <p>Target</p>
                                    <div class="col-md-12">
                                        <div class="row">
                                            <div class="col-md-1">
                                                <input type="radio" name="data[targeting_and_audience][location][target]" value="Presence or interest: People in, regularly in, or who've shown interest in your targeted locations (recommended)" checked>
                                            </div>
                                            <div class="col-md-11">
                                                <label>Presence or interest: People in, regularly in, or who've shown interest in your targeted locations (recommended)</lable>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-1">
                                                <input type="radio" name="data[targeting_and_audience][location][target]" value="Presence: People in or regularly in your targeted locations">
                                            </div>
                                            <div class="col-md-11">
                                                <label>Presence: People in or regularly in your targeted locations</lable>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-1">
                                                <input type="radio" name="data[targeting_and_audience][location][target]" value="Search interest: People searching for your targeted locations">
                                            </div>
                                            <div class="col-md-11">
                                                <label>Search interest: People searching for your targeted locations</lable>
                                            </div>
                                        </div>
                                    </div>
                                    <p>Exclude</p>
                                    <div class="col-md-12">
                                        <div class="row">
                                            <div class="col-md-1">
                                                <input type="radio" name="data[targeting_and_audience][location][exlude]" value="Presence: People in your excluded locations (recommended)" checked>
                                            </div>
                                            <div class="col-md-11">
                                                <label>Presence: People in your excluded locations (recommended)</lable>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-1">
                                                <input type="radio" name="data[targeting_and_audience][location][exlude]" value="Presence or interest: People in, regularly in, or who’ve shown interest in your excluded locations">
                                            </div>
                                            <div class="col-md-11">
                                                <label>Presence or interest: People in, regularly in, or who’ve shown interest in your excluded locations</lable>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row campanin-type-child child-campaning-goal">
                <div class="col-md-12">
                    <div class="col-md-4">
                        <br>
                        <br>
                        <label class="control-label" for="rolename">Languages</label>
                    </div>
                    <div class="col-md-8">
                        <br>
                        <br>
                        <div class="form-group" style="margin-left:-30px">
                            <div class="col-md-12">
                                <select id="dates-field2" class="multiselect-ui form-control" name="data[targeting_and_audience][language]" multiple="multiple" >
                                    <option value="English">English</option>
                                    <option value="Hindi">Hindi</option>
                                </select>
                            </div>
                        </div>
                        <br>
                        <br>
                        <br>
                    </div>
                </div>
            </div>
            <div class="row campanin-type-child child-campaning-goal">
                <div class="col-md-12">
                    <div class="">
                        <div class="col-md-4">
                            <br>
                            <br>
                            <label>Audiences</lable>
                        </div>
                        <div class="col-md-6">
                            <p>Audiences targeting setting for this campaign</p>
                            <div class="row">
                                <div class="col-md-1">
                                    <input type="radio" name="data[targeting_and_audience][audiences]" value="Targeting" checked>
                                </div>
                                <div class="col-md-11">
                                    <label>Targeting</lable>
                                    <br><span style="font-size: 12px;">Narrow the reach of your campaign to the selected audiences, with the option to adjust the bids</span>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-1">
                                    <input type="radio" name="data[targeting_and_audience][audiences]" value="Observation (recommended)">
                                </div>
                                <div class="col-md-11">
                                    <label>Observation (recommended)</lable>
                                    <br><span style="font-size: 12px;">Don't narrow the reach of your campaign, with the option to adjust the bids on the selected audiences</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row campanin-type-child child-campaning-goal">
                <div class="col-md-12">
                    <h3 class="col-sm-12">Budget and bidding</h3>
                    <p class="col-sm-12">Define how much you want to spend and how you want to spend it</p>
                </div>
                <div class="col-md-12">
                    <div class="">
                        <div class="col-md-4">
                            <br>
                            <br>
                            <label>Name</lable>
                        </div>
                        <div class="col-md-6">
                            <div class="row">
                                <div class="col-md-12">
                                    <p>Enter the budget name</p>
                                    <input type="text" name="data[budget_and_bidding][name]" class="form-control location_radio" required="">
                                    <br>
                                    <br>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="">
                        <div class="col-md-4">
                            <br>
                            <br>
                            <label>Budget</lable>
                        </div>
                        <div class="col-md-6">
                            <div class="row">
                                <div class="col-md-12">
                                    <p>Enter the average you want to spend each day</p>
                                    <input type="number" name="data[budget_and_bidding][budget]" class="form-control location_radio" required="">
                                    <br>
                                    <br>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="">
                        <div class="col-md-4">
                            <br>
                            <br>
                            <label>Bidding</lable>
                        </div>
                        <div class="col-md-6">
                            <div class="row">
                                <div class="col-md-12">
                                    <p>What do you want to focus on?</p>
                                    <select class="form-control" id="biding_select" name="data[budget_and_bidding][bidding][focus]">
                                        <optgroup label="Recommended">
                                            <option value="TARGET_CPA">Conversions</option>
                                            <option value="TARGET_ROAS">Conversion value</option>
                                        </optgroup>
                                        <optgroup label="Other optimization options">
                                            <option value="TARGET_SPEND">Clicks</option>
                                            <option value="MANUAL_CPM">Impression share</option>
                                        </optgroup>
                                    </select>
                                </div>
                            </div>
                            <div class="row bidding-sections">
                                <div class="col-md-12">
                                    <br>
                                    <span style="font-size: 12px;">Target CPA</span>
                                    <input type="number" step="0.00" name="data[budget_and_bidding][bidding][cpa]" class="form-control">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row campanin-type-child child-campaning-goal">
                <div class="col-md-12">
                    <a href="#demox" class="col-sm-12" data-toggle="collapse">Show more settings</a>
                    <div id="demox" class="collapse col-sm-12">
                        <div class="row">
                            <div class="col-md-4">
                                <label>Conversions</lable>
                            </div>
                            <div class="col-md-8">
                                <p>Select which conversions are included in the "Conversions" column for this campaign and used for Smart Bidding </p>
                            </div>
                            <div class="col-md-4">
                                
                            </div>
                            <div class="col-md-8">
                                <div class="row">
                                    <div class="col-md-1">
                                        <input type="radio" name="data[budget_and_bidding][budget_and_bidding][conversions]" checked value="Use the account-level Include in 'Conversions' setting" />
                                    </div>
                                    <div class="col-md-7">
                                        <label>Use the account-level "Include in 'Conversions'" setting</lable>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <label>Ad rotation</lable>
                            </div>
                            <div class="col-md-8">
                                <div class="row">
                                    <div class="col-md-1">
                                        <input type="radio" checked name="data[budget_and_bidding][budget_and_bidding][ad_rotation]" value="Optimize: Prefer best performing ads" />
                                    </div>
                                    <div class="col-md-7">
                                        <label>Optimize: Prefer best performing ads</lable>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-1">
                                        <input type="radio" name="data[budget_and_bidding][budget_and_bidding][ad_rotation]" value="Do not optimize: Rotate ads indefinitely"/>
                                    </div>
                                    <div class="col-md-7">
                                        <label>Do not optimize: Rotate ads indefinitely</lable>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-1">
                                        <input type="radio" name="data[budget_and_bidding][budget_and_bidding][ad_rotation]" value="Optimize for conversions (Not supported)" />
                                    </div>
                                    <div class="col-md-7">
                                        <label>Optimize for conversions (Not supported)</lable>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-1">
                                        <input type="radio" name="data[budget_and_bidding][budget_and_bidding][ad_rotation]" value="Rotate evenly (Not supported)" />
                                    </div>
                                    <div class="col-md-7">
                                        <label>Rotate evenly (Not supported)</lable>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row campanin-type-child child-campaning-goal">
                <div class="col-md-12">
                    <h3 class="col-sm-12">Ad extensions</h3>
                    <p class="col-sm-12">Get up to 15% higher clickthrough rate by showing additional information on your ads</p>
                </div>
                <div class="col-md-12">
                    <div class="">
                        <div class="col-md-4">
                            <label>Sitelink extensions</lable>
                        </div>
                        <div class="col-md-6 sitelink-url-section">
                            <div class="row">
                                <div class="col-md-12">
                                    <input type="url" name="data[ads_extension][url][]" class="form-control location_radio" placeholder="Enter url">
                                    <br>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12 text-right">
                                    <button type="button" id="addSitelink" class="btn btn-default">
                                        Add
                                    </button>
                                    <br>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            `
    $('.create-campaning-phase-2').html($html);
    $('#phase-1-type').text($('#campanin-type').val());
    $('#phase-1-goal').text($('#goal').val());
    $('#dates-field2').multiselect({
        includeSelectAllOption: true,
        selectAllText: 'All Languages',
        buttonWidth:'100%',
        maxHeight:200,
        dropUp:true,
    });
    $('#create-camp-btn').show()
    $('#continue-phase-1').hide();
    $('#addAccountStatus').val(2);
}
$(document).on('change','#biding_select',function(){
    $('.bidding-sections').remove();
    let value = $(this).val();
    let biddingSelect = '';
    if (value == 'TARGET_CPA'){
        biddingSelect = `<div class="row bidding-sections">
                            <div class="col-md-12">
                                <br>
                                <span style="font-size: 10px;">Target CPA</span>
                                <input type="number" step="0.00" name="data[budget_and_bidding][bidding][cpa]" class="form-control">
                            </div>
                        </div>`;        
    }
    else if(value == 'TARGET_ROAS'){
        biddingSelect = `<div class="row bidding-sections">
                            <div class="col-md-12">
                                <br>
                                <span style="font-size: 10px;">Target ROAS</span>
                                <input type="number" step="0.00" name="data[budget_and_bidding][bidding][roas]" class="form-control">
                            </div>
                        </div>`; 
    }
    else if(value == 'TARGET_SPEND'){
        biddingSelect = `<div class="row bidding-sections">
                            <div class="col-md-12">
                                <br>
                                <span style="font-size: 10px;">Maximum CPC bid limit</span>
                                <input type="number" step="0.00" name="data[budget_and_bidding][bidding][cpc]" class="form-control">
                            </div>
                        </div>`; 
    }
    else if(value == 'MANUAL_CPM'){
        biddingSelect = `<div class="row bidding-sections">
                            <div class="col-md-12">
                                <br>
                                <span style="font-size: 10px;">Where do you want your ads to appear</span>
                                <select name="data[budget_and_bidding][bidding][where_do_ad_appear]" class="form-control">
                                    <option>Anywhere on results page</option>
                                    <option>Top of results page</option>
                                    <option>Absolute top of results page</option>
                                </select>
                            </div>
                            <div class="col-md-12">
                                <br>
                                <span style="font-size: 10px;">Percent (%) impression share to targe</span>
                                <input type="number" step="0.00" name="data[budget_and_bidding][bidding][percent_impression_share_to_target]" class="form-control">
                            </div>
                            <div class="col-md-12">
                                <br>
                                <span style="font-size: 10px;">Maximum CPC bid limit</span>
                                <input type="number" step="0.00" name="data[budget_and_bidding][bidding][max_cpc_bit_limit]" class="form-control">
                            </div>
                        </div>`; 
    }
    $(this).parent().parent().after(biddingSelect);
});
$(document).on('change','.location_radio',function(){
    if ($(this).val() == "Enter another location"){
        $(this).parent().parent().append('<div class="col-md-12"><input type="text" name="customcountry" class="form-control customcountryinput" placeholder="Enter a location to target or exclude" /> </div>')
    }else{
        $('.customcountryinput').remove();
    }
});
$(document).on('click','#addSitelink',function(){
    $(this).before(`<div class="row">
                        <div class="col-md-12">
                            <input type="url" name="data[ads_extension][url][]" class="form-control location_radio" placeholder="Enter url">
                            <br>
                        </div>
                    </div>`);
})
let adgroupCount = 1;
$(document).on('click','#removeAdGroupSecion',function(){
    $(this).parent().parent().remove();
});
$(document).on('click','#addmoreGroup',function(){
    $('#addGroupbefore').before(`<div class="form-group row" style="margin:10px 0px 10px 0px;margin-top:10px;margin-bottom:10px; border: 1px solid #f2f2f2; padding: 20px 0px 20px 0px;">
                        <div class="col-md-12" style="text-align: right; margin-top: -20px; margin-left: 28px;">
                            <i class="fa fa-close" id="removeAdGroupSecion"></i>
                        </div>
                        <div class="col-md-6">
                            <span>Ad group name</span>
                            <input type="text" name="adgroup[${adgroupCount}][name]" class="form-control" placeholder="Enter Ad group name" value="Ad group ${adgroupCount+1}" required="">
                        </div>
                        <div class="col-md-6">
                            <div class="row">
                                <div class="col-md-12">
                                    <span class="mt-5">Keywords</span>
                                </div>
                                <div class="col-md-12">
                                    <input type="url" name="adgroup[${adgroupCount}][url]" class="form-control" placeholder="Enter relate web page URL " required="">
                                </div>
                                <div class="col-md-12 mt-3">
                                    <input type="text" value="" name="adgroup[${adgroupCount}][keywords]" class="form-control" placeholder="Enter Keywords" required=""/>
                                </div>
                                <div class="col-md-12 mt-3">
                                    <input type="number" step="0.00" name="adgroup[${adgroupCount}][budget]" class="form-control" placeholder="Enter budget" required="">
                                </div>
                            </div>
                        </div>
                    </div>`)
    adgroupCount++
    $(".taginput").tagsinput('items')
    $('.bootstrap-tagsinput').css('width', '100%');
});
$(document).on('click','#addHeadline',function(){
    $(this).parent().before(`
                        <div class="col-md-12 mt-2 mb-2">
                            <input type="text" name="headlines[]" class="form-control" placeholder="New headline">
                        </div>`)
});
$(document).on('click','#addDescriptions',function(){
    $(this).parent().before(`
                        <div class="col-md-12 mt-2 mb-2">
                            <input type="text" name="descriptions[]" class="form-control" placeholder="New descriptions">
                        </div>`)
});
let customParamCount = 1;
$(document).on('click','#addCustomParam',function(){
    $(this).parent().parent().before(`<div class="row mb-4">
                                            <div class="col-md-6">
                                                <input type="text" name="customparam[${customParamCount}][name]" class="form-control" placeholder="Name">
                                            </div>
                                            <div class="col-md-6">
                                                <input type="text" name="customparam[${customParamCount}][value]" class="form-control" placeholder="Value">
                                            </div>
                                        </div>`);
    customParamCount++
});
$(document).on('change','#different_url_mobile',function(){
    if ($(this).is(':checked')) {
        $('.mobile-url-container').show();
    }else{
        $('.mobile-url-container').hide();
    }
});
var page = {
    init: function(settings) {
        
        page.config = {
            bodyView: settings.bodyView
        };
        
        $.extend(page.config, settings);
        
        this.getResults();

        //initialize pagination
        page.config.bodyView.on("click",".page-link",function(e) {
        	e.preventDefault();
        	page.getResults($(this).attr("href"));
        });

        page.config.bodyView.on("click",".btn-search-action",function(e) {
            e.preventDefault();
            page.getResults();
        });

        // page.config.bodyView.on("click",".btn-add-action",function(e) {
        //     e.preventDefault();
        //     page.createRecord();
        // });

        $(".common-modal").on("click",".submit-platform",function() {
            page.submitPlatform($(this));
        });

        // delete product templates
        page.config.bodyView.on("click",".btn-delete-template",function(e) {
            if(!confirm("Are you sure you want to delete record?")) {
                return false;
            }else {
                page.deleteRecord($(this));
            }
        });

        page.config.bodyView.on("click",".btn-edit-template",function(e) {
            page.editRecord($(this));
        });

        page.config.bodyView.on("click",".btn-add-components",function(e) {
            page.addComponents($(this));
        });

        $(".common-modal").on("click",".update-components-btn",function(e) {
            e.preventDefault();
            page.submitComponents($(this));
        });
    },
    validationRule : function(response) {
         $(document).find("#product-template-from").validate({
            rules: {
                name     : "required",
            },
            messages: {
                name     : "Template name is required",
            }
        })
    },
    loadFirst: function() {
        var _z = {
            url: this.config.baseUrl + "/ads/records",
            method: "get",
            beforeSend : function() {
                $("#loading-image").show();
            }
        }
        this.sendAjax(_z, "showResults");
    },
    getResults: function(href) {
    	var _z = {
            url: (typeof href != "undefined") ? href : this.config.baseUrl + "/ads/records",
            method: "get",
            data : $(".message-search-handler").serialize(),
            beforeSend : function() {
                $("#loading-image").show();
            }
        }
        this.sendAjax(_z, "showResults");
    },
    showResults : function(response) {
        $("#loading-image").hide();
    	var addProductTpl = $.templates("#template-result-block");
        var tplHtml       = addProductTpl.render(response);

        $(".count-text").html("("+response.total+")");

    	page.config.bodyView.find("#page-view-result").html(tplHtml);

    }
    ,
    deleteRecord : function(ele) {
        var _z = {
            url: (typeof href != "undefined") ? href : this.config.baseUrl + "/ads/"+ele.data("id")+"/delete",
            method: "get",
            beforeSend : function() {
                $("#loading-image").show();
            }
        }
        this.sendAjax(_z, 'deleteResults');
    },
    deleteResults : function(response) {
        if(response.code == 200){
            this.getResults();
            toastr['success']('Message deleted successfully', 'success');
        }else{
            toastr['error']('Oops.something went wrong', 'error');
        }

    },
    createRecord : function(response) {
        var createWebTemplate = $.templates("#template-create-platform");
        var tplHtml = createWebTemplate.render({data:{}});
        
        var common =  $(".common-modal");
            common.find(".modal-dialog").html(tplHtml); 
            common.modal("show");
    },

    editRecord : function(ele) {
        var _z = {
            url: (typeof href != "undefined") ? href : this.config.baseUrl + "/ads/"+ele.data("id")+"/edit",
            method: "get",
        }
        this.sendAjax(_z, 'editResult');
    },

    editResult : function(response) {
        var createWebTemplate = $.templates("#template-create-platform");
        var tplHtml = createWebTemplate.render(response);
        var common =  $(".common-modal");
            common.find(".modal-dialog").html(tplHtml); 
            common.modal("show");
    },
    submitPlatform : function(ele) {
        var _z = {
            url: (typeof href != "undefined") ? href : this.config.baseUrl + "/ads/save",
            method: "post",
            data : ele.closest("form").serialize(),
            beforeSend : function() {
                $("#loading-image").show();
            }
        }
        this.sendAjax(_z, "saveSite");
    },
    
    assignSelect2 : function () {
        var selectList = $("select.select-searchable");
            if(selectList.length > 0) {
                $.each(selectList,function(k,v){
                    var element = $(v);
                    if(!element.hasClass("select2-hidden-accessible")){
                        element.select2({tags:true,width:"100%"});
                    }
                });
            }
    },
    saveSite : function(response) {
        if(response.code  == 200) {
            page.loadFirst();
            $(".common-modal").modal("hide");
        }else {
            $("#loading-image").hide();
            toastr["error"](response.error,"");
        }
    },
    addComponents : function(ele) {
        var _z = {
            url: (typeof href != "undefined") ? href : this.config.baseUrl + "/ads/"+ele.data("id")+"/components",
            method: "get",
        }
        this.sendAjax(_z, 'afterResponsecomponents');
    },
    afterResponsecomponents : function(response) {
        var createWebTemplate = $.templates("#template-create-components");
        var tplHtml = createWebTemplate.render(response);
        var common =  $(".common-modal");
            common.find(".modal-dialog").html(tplHtml); 
            common.modal("show");
        $(".select2-components-tags").select2({tags : true});
    },
    submitComponents : function(ele) {
        var _z = {
            url: this.config.baseUrl + "/ads/"+ele.data("id")+"/components",
            method: "post",
            data : ele.closest("form").serialize(),
            beforeSend : function() {
                $("#loading-image").show();
            }
        }
        this.sendAjax(_z, "afterSubmitComponents");
    },
    afterSubmitComponents : function(response) {
        if(response.code  == 200) {
            page.loadFirst();
            $(".common-modal").modal("hide");
        }else {
            $("#loading-image").hide();
            toastr["error"](response.error,"");
        }
    },
}

$.extend(page, common);