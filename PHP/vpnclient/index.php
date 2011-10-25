<?php

echo "<html>
<head>
        <title>How to connect to a VPN with the Cisco VPN client</title>
</head>
<body>";

function indent($count = 4)
{
    $return = "";
    while ($count != 0)
    {
        $return .= " ";
        $count--;
    }
    return $return;
}

function line_break($count = 1, $indent = 0)
{
    $return = "\n" . indent($indent);
    while ($count != 0)
    {
        $return .= "<br>";
        $count--;
    }
    $return .= "\n";
    return $return;
}

$image_prefix = "vpnclient_";
$image_extension = ".jpg";

$messages = array(
    0   => "Click the button inside the red square. This will allow us to setup a new VPN connection.",
    1   => "Fill in the boxes as show, giving the correct information for each one. The entry point is where you are entering into the VPN. So if you had multiple groups on the VPN, this would allow us to distinguish which one is which. The description just gives more detail about the entry point. Then the ip of the outside interface. followed by the group authentication for which group we are connecting too. The information in the boxes shown is the information that was used during testing and first attempt connection.",
    2   => "This tab can be left alone if you do not want to set it to IPsec over TCP, in which case you need to open up the port in which you are connecting to in the firewall. The test connection that worked used IPsec over UDP. So we can leave it like that.",
    3   => "Not used.",
    4   => "Unless someone is connecting over dial-up we can leave this tab alone as well.",
    5   => "This is what your vpnclient should look like after you save the connection. The fields might be different given the different information, but they should be very similar. Now to connect we need to either double click on the connection we are wanting to open or click the connection we want then click the connect button."
);

echo "\n";

echo "<a href='vpnclient-msi.exe'>Download VPN Client</a>" . line_break(3, 4);

for ($index = 0; $index <= 5; $index++)
{
    echo indent(4) . $messages[$index] . line_break(1, 4);
    echo indent(4) . "<img src=\"" . $image_prefix . $index . $image_extension . "\"></img>" . line_break(3, 4);
}

echo "</body>
</html>";

?>
