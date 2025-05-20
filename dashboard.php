<header>
    <div>
        <!-- Hotel name/logo goes here -->
        <h1>Novotel</h1>
    </div>
    <div>
        <!-- Search bar goes here -->
        <input type="text" placeholder="Search for rooms and offers">
    </div>
    <div>
        <!-- Date and user info go here -->
        <span>Friday, November 18, 2022</span>
        <button>Create booking</button>
        <!-- Notification and user icon placeholders -->
        <span>[Notification Icon]</span>
        <span>[User Icon]</span>
    </div>
</header>

<div style="display: flex;">
    <aside style="width: 200px; padding: 20px; border-right: 1px solid #ccc;">
        <ul>
            <li><a href="#">Dashboard</a></li>
            <li><a href="#">Front desk</a></li>
            <li><a href="#">Guest</a></li>
            <li><a href="#">Rooms</a></li>
            <li><a href="#">Deal</a></li>
            <li><a href="#">Rate</a></li>
        </ul>
    </aside>
    <main style="flex-grow: 1; padding: 20px;">
        <h2>Overview</h2>
        <div style="display: flex; justify-content: space-around; margin-top: 20px;">
            <div style="border: 1px solid #ccc; padding: 15px; text-align: center;">
                <h3>Today's Check-in</h3>
                <p>23</p>
            </div>
            <div style="border: 1px solid #ccc; padding: 15px; text-align: center;">
                <h3>Today's Check-out</h3>
                <p>13</p>
            </div>
            <div style="border: 1px solid #ccc; padding: 15px; text-align: center;">
                <h3>Total In hotel</h3>
                <p>60</p>
            </div>
            <div style="border: 1px solid #ccc; padding: 15px; text-align: center;">
                <h3>Total Available room</h3>
                <p>10</p>
            </div>
            <div style="border: 1px solid #ccc; padding: 15px; text-align: center;">
                <h3>Total Occupied room</h3>
                <p>90</p>
            </div>
        </div>
        <h2>Rooms</h2>
        <div style="display: flex; justify-content: space-around; margin-top: 20px;">
            <div style="border: 1px solid #ccc; padding: 15px; text-align: center;">
                <h3>Single sharing</h3>
                <p>2/30</p>
                <p>$ 568 /day</p>
            </div>
            <div style="border: 1px solid #ccc; padding: 15px; text-align: center;">
                <h3>Double sharing</h3>
                <p>2/35</p>
                <p>$ 1,068 /day</p>
            </div>
            <div style="border: 1px solid #ccc; padding: 15px; text-align: center;">
                <h3>Triple sharing</h3>
                <p>2/25</p>
                <p>$ 1,568 /day</p>
            </div>
            <div style="border: 1px solid #ccc; padding: 15px; text-align: center;">
                <h3>VIP Suit</h3>
                <p>4/10</p>
                <p>$ 2,568 /day</p>
            </div>
        </div>
        
        <div style="display: flex; margin-top: 20px;">
            <div style="flex: 1; border: 1px solid #ccc; padding: 15px; margin-right: 10px;">
                <h2>Room status</h2>
                <p>Occupied rooms: 104</p>
                <p>Clean: 90</p>
                <p>Dirty: 4</p>
                <p>Inspected: 60</p>
                <!-- Room status content goes here -->
            </div>
            <div style="flex: 1; border: 1px solid #ccc; padding: 15px;">
                <h2>Floor status</h2>
                <p>80%</p>
                <p>Competed</p>
                <p>Yet to Complete</p>
                <!-- Floor status content goes here -->
            </div>
        </div>

        <div style="display: flex; margin-top: 20px;">
            <div style="flex: 2; border: 1px solid #ccc; padding: 15px; margin-right: 10px;">
                <h2>Occupancy Statistics</h2>
                <!-- Chart will go here, requires a charting library and data -->
            </div>
            <div style="flex: 1; border: 1px solid #ccc; padding: 15px;">
                <h2>Customers feedback</h2>
                <div>
                    <h3>Mark</h3>
                    <p>Food could be better.</p>
                    <p>A201</p>
                </div>
                <div style="margin-top: 10px;">
                    <h3>Christian</h3>
                    <p>Facilities are not enough for amount paid.</p>
                    <p>A101</p>
                </div>
                <div style="margin-top: 10px;">
                    <h3>Alexander</h3>
                    <p>Room cleaning could be better.</p>
                    <p>A301</p>
                </div>
                <!-- Customers feedback content goes here -->
            </div>
        </div>

        <!-- Main content area will go here -->
    </main>
</div> 