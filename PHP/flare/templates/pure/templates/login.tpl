<!-- Header section -->
<body id='login_body'>
    <div id='login_header'>
        <div id='login_title'>{$_WELCOME_BANNER}</div>
        <br />
    </div>

    <p />

    <!-- Login section -->
    <div id='login_creds'>
        <form method='post' action='index.php'>
            <input type='hidden' name='extension' value='Authentication'>
            <input type='hidden' name='action' value='login'>
            <input type='text' name='username' value='username' class='top'>
            <p />
            <input type='password' name='password' value='******' class='middle'>
            <p />
            <input type='submit' name='submit' value='SIGN IN' class='bottom'>
        </form>
    </div>

    <!-- Create an account section -->
    <div id='login_create'>
        Dont have an account? Your missing out!<p />

        Each Flare user receives...
        <ul>
            <li>1 GB of file storage<p />
            <li>That storage doubles as <br />your own website at Tech<p />
            <li>Ability to easily share <br />files with groups of friends<p />
        </ul>
        If you do not have an account, <br />
        register now by clicking
        <a href='index.php?extension=Accounts&amp;action=create_account'>here</a>.
    </div>
