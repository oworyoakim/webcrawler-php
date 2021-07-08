<?php


namespace App\Traits;


trait MockedData
{
    private function mockedProperties()
    {
        return [
            [
                'name' => "The jefferson modern apartment with view to central park",
                'PID' => "1"
            ],
            [
                'name' => "The jefferson modern apartment with view to central park",
                'PID' => "2"
            ],
            [
                'name' => "The jefferson modern apartment with view to central park",
                'PID' => "3"
            ],
        ];
    }

    private function mockedBusinessEssentials()
    {
        return [
            [
                'type' => "noise-control",
                'enabled' => false,
                'totalProperties' => 10,
                'connectedProperties' => 4,
            ],
            [
                'type' => "self-check-in",
                'enabled' => true,
                'totalProperties' => 5,
                'connectedProperties' => 3,
            ],
            /*
            [
                'type' => "email-communications",
                'enabled' => true,
                'totalProperties' => 5,
                'connectedProperties' => 2,
            ],
            */
        ];
    }

    private function mockedMessageTemplates()
    {
        return [
            [
                'time' => 15,
                'template' => "Hello [GuestName], this is [AgentName]....",
            ],
            [
                'time' => 30,
                'template' => "Hello [GuestName], this is [AgentName]....",
            ],
            [
                'time' => 45,
                'template' => "Hello [GuestName], this is [AgentName]....",
            ],
        ];
    }
}
