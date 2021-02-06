#include <sourcemod>
#include <sdktools>

public Plugin myinfo = 
{
	name = "ACP | Help Menu", 
	author = "PyNiO ™", 
	version = "1.0.0", 
	url = "https://steamcommunity.com/id/pynioanime/"
}

Menu g_mainServerMenu;
Menu g_CommandMenu;
Menu g_StatsMenu;
Menu g_ServersMenu;
Menu g_DetailsMenu;
Menu g_AdminsMenu;
Panel g_VipPanel;

public void OnPluginStart()
{
	RegConsoleCmd("sm_menu", cmd_MainServerMenu);
	RegConsoleCmd("sm_help", cmd_MainServerMenu);
	RegConsoleCmd("sm_pomoc", cmd_MainServerMenu);
	
	RegConsoleCmd("sm_vip", cmd_VipMenu);
	
	RegConsoleCmd("sm_komendy", cmd_CommandMenu);
	
	RegConsoleCmd("sm_statystki", cmd_StatsMenu);
	
	RegConsoleCmd("sm_serwery", cmd_ServersMenu);
	
	RegConsoleCmd("sm_admini", cmd_AdminsMenu);
}

public Action cmd_MainServerMenu(int client, int args)
{
	BuildMenu();
	g_mainServerMenu.Display(client, MENU_TIME_FOREVER);
	return Plugin_Handled;
}

void BuildMenu()
{
	g_mainServerMenu = new Menu(MainServerMenu_Handler);
	g_mainServerMenu.SetTitle("[S-D] Menu pomocy", MENU_ACTIONS_ALL);
	
	char path[256];
	BuildPath(Path_SM, path, sizeof(path), "configs/acp_main_menu.cfg");
	
	KeyValues keyValues = new KeyValues("helpmenu");
	keyValues.ImportFromFile(path);
	
	if (!keyValues.GotoFirstSubKey()) {
		PrintToServer("*** I had a problem while building a menu :/ Check main_menu.cfg");
		delete keyValues;
		return;
	}
	
	char command[64], itemName[64];
	do {
		keyValues.GetString("komenda", command, sizeof(command));
		keyValues.GetString("nazwa", itemName, sizeof(itemName));
		
		g_mainServerMenu.AddItem(command, itemName);
	} while (keyValues.GotoNextKey());
	
	delete keyValues;
}

public int MainServerMenu_Handler(Menu menu, MenuAction action, int param1, int param2)
{
	switch (action)
	{
		case MenuAction_Select:
		{
			int client = param1;
			
			char info[64];
			menu.GetItem(param2, info, sizeof(info));
			ClientCommand(client, info);
		}
	}
}


public Action cmd_VipMenu(int client, int args)
{
	BuildPanelVip();
	g_VipPanel.DrawItem("Zamknij");
	
	g_VipPanel.Send(client, PanelHandler1, 20);
	
	delete g_VipPanel;
	
	return Plugin_Handled;
}

void BuildPanelVip()
{
	g_VipPanel = new Panel();
	g_VipPanel.SetTitle("★ Funkcje VIP'a ★");
	
	char path[256];
	BuildPath(Path_SM, path, sizeof(path), "configs/acp_vip_panel.cfg");
	
	KeyValues keyValues = new KeyValues("vippanel");
	keyValues.ImportFromFile(path);
	
	if (!keyValues.GotoFirstSubKey()) {
		PrintToServer("*** I had a problem while building a menu :/ Check vip_panel.cfg");
		delete keyValues;
		return;
	}
	
	char itemName[64], NR[24], opis[128];
	do {
		keyValues.GetString("nazwa", itemName, sizeof(itemName));
		keyValues.GetString("nr", NR, sizeof(NR));
		Format(opis, sizeof(opis), "» %s", itemName);
		
		g_VipPanel.DrawItem(opis, ITEMDRAW_RAWLINE);
	} while (keyValues.GotoNextKey());
	
	delete keyValues;
}

public int PanelHandler1(Menu panel, MenuAction action, int client, int itemNum)
{
	if (itemNum == 9)
	{
		CloseHandle(panel);
	}
	else
	{
		ClientCommand(client, "sm_freevip");
		CloseHandle(panel);
	}
}

public Action cmd_CommandMenu(int client, int args)
{
	BuildMenuCommand();
	g_CommandMenu.Display(client, MENU_TIME_FOREVER);
	return Plugin_Handled;
}

void BuildMenuCommand()
{
	g_CommandMenu = new Menu(CommandMenu_Handler);
	g_CommandMenu.SetTitle("[S-D] Komendy", MENU_ACTIONS_ALL);
	
	char path[256];
	BuildPath(Path_SM, path, sizeof(path), "configs/acp_command_menu.cfg");
	
	KeyValues keyValues = new KeyValues("komendy");
	keyValues.ImportFromFile(path);
	
	if (!keyValues.GotoFirstSubKey()) {
		PrintToServer("*** I had a problem while building a menu :/ Check command_menu.cfg");
		delete keyValues;
		return;
	}
	
	char command[64], itemName[64], opis[64];
	do {
		keyValues.GetString("komenda", command, sizeof(command));
		keyValues.GetString("opis", itemName, sizeof(itemName));
		
		Format(opis, sizeof(opis), "%s - %s", command, itemName);
		
		g_CommandMenu.AddItem(command, opis);
	} while (keyValues.GotoNextKey());
	
	delete keyValues;
}

public int CommandMenu_Handler(Menu menu, MenuAction action, int param1, int param2)
{
	switch (action)
	{
		case MenuAction_Select:
		{
			int client = param1;
			
			char info[64];
			menu.GetItem(param2, info, sizeof(info));
			ClientCommand(client, info);
		}
	}
}

public Action cmd_StatsMenu(int client, int args)
{
	BuildStatsMenu();
	g_StatsMenu.Display(client, MENU_TIME_FOREVER);
	return Plugin_Handled;
}

void BuildStatsMenu()
{
	g_StatsMenu = new Menu(StatsMenu_Handler);
	g_StatsMenu.SetTitle("[S-D] Menu Statystyk", MENU_ACTIONS_ALL);
	
	char path[256];
	BuildPath(Path_SM, path, sizeof(path), "configs/acp_stats_menu.cfg");
	
	KeyValues keyValues = new KeyValues("statystyki");
	keyValues.ImportFromFile(path);
	
	if (!keyValues.GotoFirstSubKey()) {
		PrintToServer("*** I had a problem while building a menu :/ Check stats_menu.cfg");
		delete keyValues;
		return;
	}
	
	char command[64], itemName[64];
	do {
		keyValues.GetString("komenda", command, sizeof(command));
		keyValues.GetString("opis", itemName, sizeof(itemName));
		
		g_StatsMenu.AddItem(command, itemName);
	} while (keyValues.GotoNextKey());
	
	delete keyValues;
}

public int StatsMenu_Handler(Menu menu, MenuAction action, int param1, int param2)
{
	switch (action)
	{
		case MenuAction_Select:
		{
			int client = param1;
			
			char info[64];
			menu.GetItem(param2, info, sizeof(info));
			ClientCommand(client, info);
		}
	}
}









public Action cmd_ServersMenu(int client, int args)
{
	BuildServersMenu();
	g_ServersMenu.Display(client, MENU_TIME_FOREVER);
	return Plugin_Handled;
}

void BuildServersMenu()
{
	g_ServersMenu = new Menu(ServersMenu_Handler);
	g_ServersMenu.SetTitle("[S-D] Lista Serwerów", MENU_ACTIONS_ALL);
	
	char path[256];
	BuildPath(Path_SM, path, sizeof(path), "configs/acp_servers_menu.cfg");
	
	KeyValues keyValues = new KeyValues("listserwer");
	keyValues.ImportFromFile(path);
	
	if (!keyValues.GotoFirstSubKey()) {
		PrintToServer("*** I had a problem while building a menu :/ Check servers_menu.cfg");
		delete keyValues;
		return;
	}
	
	char name[64], players[64], slots[64], ID[24], item[128];
	do {
		keyValues.GetString("nazwa", name, sizeof(name));
		keyValues.GetString("graczy", players, sizeof(players));
		keyValues.GetString("sloty", slots, sizeof(slots));
		keyValues.GetString("ID", ID, sizeof(ID));
		
		Format(item, sizeof(item), "%s [%s / %s]", name, players, slots);
		
		g_ServersMenu.AddItem(ID, item);
	} while (keyValues.GotoNextKey());
	
	delete keyValues;
}

public int ServersMenu_Handler(Menu menu, MenuAction action, int param1, int param2)
{
	switch (action)
	{
		case MenuAction_Select:
		{
			int client = param1;
			
			char info[64];
			menu.GetItem(param2, info, sizeof(info));
			ServerDetails(client, info);
		}
	}
}





void ServerDetails(int client, char[] ID)
{
	g_DetailsMenu = new Menu(DetailsMenu_Handler);
	
	
	char path[256];
	BuildPath(Path_SM, path, sizeof(path), "configs/acp_details_menu.cfg");
	
	KeyValues keyValues = new KeyValues("listserwer");
	keyValues.ImportFromFile(path);
	
	if (!keyValues.GotoFirstSubKey()) {
		PrintToServer("*** I had a problem while building a menu :/ Check details_menu.cfg");
		delete keyValues;
		return;
	}
	
	char name[64], players[64], slots[64], map[64], ipaddress[128], iden[24], tyt[256];
	do {
		keyValues.GetString("nazwa", name, sizeof(name));
		keyValues.GetString("graczy", players, sizeof(players));
		keyValues.GetString("sloty", slots, sizeof(slots));
		keyValues.GetString("mapa", map, sizeof(map));
		keyValues.GetString("ip", ipaddress, sizeof(ipaddress));
		keyValues.GetString("id", iden, sizeof(iden));
		
		if (StrEqual(iden, ID))
		{
			Format(tyt, sizeof(tyt), "Nazwa: %s\nGraczy: %s/%s\nMapa: %s\nIP: %s", name, players, slots, map, ipaddress);
			
			g_DetailsMenu.SetTitle("%s", tyt, MENU_ACTIONS_ALL);
			
			
			g_DetailsMenu.AddItem(ipaddress, "Połącz");
			
			g_DetailsMenu.Display(client, MENU_TIME_FOREVER);
		}
		
	} while (keyValues.GotoNextKey());
	
	delete keyValues;
}

public int DetailsMenu_Handler(Menu menu, MenuAction action, int param1, int param2)
{
	switch (action)
	{
		case MenuAction_Select:
		{
			int client = param1;
			
			char info[64];
			menu.GetItem(param2, info, sizeof(info));
			PrintToChat(client, "Aby się połączyć wpisz w konsole: connect %s", info);
			PrintToConsole(client, "Aby się połączyć wpisz w konsole: connect %s", info);
		}
	}
}



public Action cmd_AdminsMenu(int client, int args)
{
	BuildAdminsMenu();
	g_AdminsMenu.Display(client, MENU_TIME_FOREVER);
	return Plugin_Handled;
}

void BuildAdminsMenu()
{
	g_AdminsMenu = new Menu(AdminsMenu_Handler);
	g_AdminsMenu.SetTitle("[S-D] Lista Administracji", MENU_ACTIONS_ALL);
	
	char path[256];
	BuildPath(Path_SM, path, sizeof(path), "configs/acp_admins_menu.cfg");
	
	KeyValues keyValues = new KeyValues("admins");
	keyValues.ImportFromFile(path);
	
	if (!keyValues.GotoFirstSubKey()) {
		PrintToServer("*** I had a problem while building a menu :/ Check admins_menu.cfg");
		delete keyValues;
		return;
	}
	
	char nick[64], rang[64], status[64];
	new String:steamID[64];
	do {
		keyValues.GetString("nick", nick, sizeof(nick));
		keyValues.GetString("ranga", rang, sizeof(rang));
		keyValues.GetString("steamID", steamID, sizeof(steamID));
		keyValues.GetString("status", status, sizeof(status));
		
		char Itema[256];
		Format(Itema, sizeof(Itema), "%s - %s [%s]", nick, rang, status);
		
		g_AdminsMenu.AddItem(steamID, Itema);
	} while (keyValues.GotoNextKey());
	
	delete keyValues;
}

public int AdminsMenu_Handler(Menu menu, MenuAction action, int param1, int param2)
{
	switch (action)
	{
		case MenuAction_Select:
		{
			int client = param1;
			
			decl String:SteamID[20];
			menu.GetItem(param2, SteamID, sizeof(SteamID));
			
			new String:CommunityID[18];
			GetCommunityIDString(SteamID, CommunityID, sizeof(CommunityID));
			PrintToChat(client, "Kontakt: http://steamcommunity.com/profiles/%s", CommunityID);
			PrintToConsole(client, "Kontakt: http://steamcommunity.com/profiles/%s", CommunityID);
		}
	}
}


stock bool:GetCommunityIDString(const String:SteamID[], String:CommunityID[], const CommunityIDSize)
{
	decl String:SteamIDParts[3][11];
	new const String:Identifier[] = "76561197960265728";
	
	if ((CommunityIDSize < 1) || (ExplodeString(SteamID, ":", SteamIDParts, sizeof(SteamIDParts), sizeof(SteamIDParts[])) != 3))
	{
		CommunityID[0] = '\0';
		return false;
	}
	
	new Current, CarryOver = (SteamIDParts[1][0] == '1');
	for (new i = (CommunityIDSize - 2), j = (strlen(SteamIDParts[2]) - 1), k = (strlen(Identifier) - 1); i >= 0; i--, j--, k--)
	{
		Current = (j >= 0 ? (2 * (SteamIDParts[2][j]-'0')) : 0) + CarryOver + (k >= 0 ? ((Identifier[k]-'0') * 1) : 0);
		CarryOver = Current / 10;
		CommunityID[i] = (Current % 10) + '0';
	}
	
	CommunityID[CommunityIDSize - 1] = '\0';
	return true;
} 