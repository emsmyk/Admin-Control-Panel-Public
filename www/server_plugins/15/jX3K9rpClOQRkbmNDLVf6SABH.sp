#include <sourcemod>
#pragma semicolon 1

new String:mapname[64];
new MapIsReloading;

public Plugin myinfo =
{
	name = "ACP | GOTV fix",
	author = "EMCE",
	version = "1.0"
};

public OnMapStart()
{
    HookEvent("player_disconnect", PlayerDisconnected,EventHookMode_Post);
    GetCurrentMap(mapname, sizeof(mapname));
    MapIsReloading = 0;
}

public Action:PlayerDisconnected(Handle:event, const String:name[], bool:dontBroadcast)
{
    decl String:s_reason[256];
    GetEventString(event,"reason", s_reason, sizeof(s_reason));
    if(StrContains(s_reason, "Punting bot", false) != -1)
    {
        if(GetClientCount() < 2 && MapIsReloading == 0)
        {
            ForceChangeLevel(mapname,"Restarting map");
            LogMessage("Restarting map");
            MapIsReloading = 1;
        }
    }
    return Plugin_Continue;
}  