#include <sdktools>
#include <clientprefs>

#pragma semicolon 1
#pragma newdecls required

public Plugin myinfo =
{
name = "ACP | RoundSound Test Music",
	author = "-",
	version = "1.0"
};


public void OnPluginStart()
{
	RegAdminCmd("sm_testmusic", CMD_Menu, ADMFLAG_ROOT);
}

public Action CMD_Menu(int client, int args)
{
    MusicMenu().Display(client, 0);
    return Plugin_Handled;
}

Menu MusicMenu()
{
    Menu menu = new Menu(Menu_Handler);
    menu.SetTitle("Muzyka:");

    ConVar cvar = FindConVar("res_tr_path");

    char sBuffer[64];
    cvar.GetString(sBuffer, sizeof(sBuffer));

    char sPath[PLATFORM_MAX_PATH];
    Format(sPath, sizeof(sPath), "sound/%s/", sBuffer);

    DirectoryListing pluginsDir = OpenDirectory(sPath);

    if(pluginsDir != null)
    {
        char fileName[128];
        while(pluginsDir.GetNext(fileName, sizeof(fileName)))
        {
            int extPosition = strlen(fileName) - 4;
            if(StrContains(fileName, ".mp3", false) == extPosition)
            {
                char sSoundName[512];
                Format(sSoundName, sizeof(sSoundName), "%s/%s", sBuffer, fileName);
                menu.AddItem(sSoundName, sSoundName);
            }
        }
    }

    delete pluginsDir;

    return menu;
}

public int Menu_Handler(Menu menu, MenuAction action, int client, int param2)
{
    switch(action)
    {
        case MenuAction_End:
        {
            delete menu;
        }
        case MenuAction_Select:
        {
            char sSound[512];
            menu.GetItem(param2, sSound, sizeof(sSound));

            Handle hVolumeCookie = FindClientCookie("abner_res_volume");

            char sCookieValue[11];
            GetClientCookie(client, hVolumeCookie, sCookieValue, sizeof(sCookieValue));

            if(StrEqual(sCookieValue, "") || StrEqual(sCookieValue, "0"))
                Format(sCookieValue , sizeof(sCookieValue), "%0.2f", 0.75);

            float fVolume = StringToFloat(sCookieValue);

            delete hVolumeCookie;

            ClientCommand(client, "playgamesound Music.StopAllMusic");

            PrecacheSound(sSound);

            EmitSoundToClient(client, sSound, _, _, _, _, fVolume);

            PrintToChat(client, "-------------------------------------------------");
            PrintToChat(client, "Piosenka: %s", sSound);
            PrintToChat(client, "Głośność: %f", fVolume);
            PrintToChat(client, "-------------------------------------------------");

            MusicMenu().DisplayAt(client, GetMenuSelectionPosition(), 0);
        }
    }
}