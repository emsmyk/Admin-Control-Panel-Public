#pragma semicolon 1
#include <csgo_colors>

StringMap Stweap;
KeyValues kfg;
bool us;
Handle tim;
	
public Plugin myinfo =
{
	name = "ACP | Reklama",
	author = "rs edit emce",
	version = "3.0"
};

enum Rtupe
{
	V = 0,
	C,
	H,
	S
}

public void OnPluginStart()
{
	if(GetEngineVersion() != Engine_CSGO) SetFailState("[Reklama] - Wtyczka dla CS:GO");
	KFG_load();
	RegAdminCmd("sm_reklama_reload", Reload_cfg, ADMFLAG_ROOT);
}

public Action Reload_cfg(int iClient, int args)
{
	KFG_load();
	return Plugin_Handled;
}

void KFG_load()
{
	if(kfg) delete kfg;
	if(Stweap) delete Stweap;
	if(tim) delete tim;
	kfg = new KeyValues("Reklama");
	static char path[128], h[1024], buf[64];
	if(!path[0]) BuildPath(Path_SM, path, 128, "configs/acp_reklama.ini");
	if(!kfg.ImportFromFile(path)) SetFailState("[Reklama] - Plik konfiguracyjny nie zostal znaleziony");
	else
	{
		kfg.Rewind();
		tim = CreateTimer(kfg.GetFloat("time"), rec, _, TIMER_REPEAT);
		kfg.JumpToKey("map");
		kfg.GotoFirstSubKey(false);
		Stweap = new StringMap();
		do
		{
			kfg.GetSectionName(h, 1024);
			kfg.GetString("", buf, 64);
			Stweap.SetString(h, buf);
		}
		while (kfg.GotoNextKey(false));
		kfg.Rewind();
		kfg.JumpToKey("text");
		kfg.GotoFirstSubKey();
		do
		{
			if(kfg.GetSectionName(h, 1024))
			{
				kfg.GetString("V", h, 1024);
				if(h[0])
				{
					Replese_constant(h, V);
					kfg.SetString("V", h);
				}
				kfg.GetString("C", h, 1024);
				if(h[0])
				{
					Replese_constant(h, C);
					kfg.SetString("C", h);
				}
				kfg.GetString("H", h, 1024);
				if(h[0])
				{
					Replese_constant(h, H);
					kfg.SetString("H", h);
				}
				kfg.GetString("S", h, 1024);
				if(h[0])
				{
					Replese_constant(h, S);
					kfg.SetString("S", h);
				}
			}
		}
		while kfg.GotoNextKey();
		us = false;
	}
}

public Action rec(Handle timer)
{
	kvup();
	static char rkl[1024];
	rkl[0]='\0';
	kfg.GetString("V", rkl, 1024);
	if(rkl[0])
	{
		Replese_st(rkl);
		VotePrintAll(rkl);
	}
	rkl[0]='\0';
	kfg.GetString("C", rkl, 1024);
	if(rkl[0])
	{
		Replese_st(rkl);
		PrintCenterTextAll(rkl);
	}
	rkl[0]='\0';
	kfg.GetString("H", rkl, 1024);
	if(rkl[0])
	{
		Replese_st(rkl);
		PrintHintTextToAll(rkl);
	}
	rkl[0]='\0';
	kfg.GetString("S", rkl, 1024);
	if(rkl[0])
	{
		Replese_st(rkl);
		CGOPrintToChatAll(rkl);
	}
	return Plugin_Continue;
}

void Replese_st(char[] rkl)
{
	static char sText[64];
	if(StrContains(rkl, "{PL}") != -1)
	{
		IntToString(GetClientCount(), sText, 64);//PL
		ReplaceString(rkl, 1024, "{PL}", sText);
	}
	if(StrContains(rkl, "{MAP}") != -1)
	{
		GetCurrentMap(sText, 64);//MAP
		Stweap.GetString(sText, sText, 64);
		ReplaceString(rkl, 1024, "{MAP}", sText);
	}
	if(StrContains(rkl, "{TIME}") != -1)
	{
		FormatTime(sText, 64, "%H:%M:%S");//TIME
		ReplaceString(rkl, 1024, "{TIME}", sText);
	}
	if (StrContains(rkl, "{TIMELEFT}") != -1)
	{
		int timeleft;
		if (GetMapTimeLeft(timeleft) && timeleft > 0)
		{
			Format(sText, 64, "%d:%02d", timeleft / 60, timeleft % 60);
			ReplaceString(rkl, 1024, "{TIMELEFT}", sText);
		}
		else ReplaceString(rkl, 1024, "{TIMELEFT}", "0");
	}
	if(StrContains(rkl, "{DATE}") != -1)
	{
		FormatTime(sText, 64, "%d/%m/%Y");
		ReplaceString(rkl, 1024, "{DATE}", sText);
	}
}

void Replese_constant(char[] rkl, Rtupe tupe)
{
	ReplaceString(rkl, 1024, "\\n", "\n");
	static char sText[3][64];
	if(!sText[0][0])
	{
		int ip = FindConVar("hostip").IntValue;
		FormatEx(sText[0], 64, "%d.%d.%d.%d", ip >>> 24 & 255, ip >>> 16 & 255, ip >>> 8 & 255, ip & 255); //IP
		GetConVarString(FindConVar("hostport"), sText[1], 64); //PORT
		IntToString(RoundToZero(1.0/GetTickInterval()), sText[2], 64);//TIC
	}
	ReplaceString(rkl, 1024, "{IP}", sText[0]);
	ReplaceString(rkl, 1024, "{PORT}", sText[1]);
	ReplaceString(rkl, 1024, "{TIC}", sText[2]);
	switch (tupe)
	{
		case H: CGOReplaceColorHsay(rkl, 1024);
		case S: CGOReplaceColorSay(rkl, 1024);
	}
}

void VotePrintAll(const char[] tx)
{
	Protobuf v = view_as<Protobuf>(StartMessageAll("VotePass", USERMSG_RELIABLE));
	v.SetInt("team", -1);
	v.SetString("disp_str", "#SFUI_Scoreboard_NormalPlayer");
	v.SetString("details_str", tx);
	v.SetInt("vote_type", 0);
	EndMessage();
}

void kvup()
{
	if(!us)
	{
		kfg.Rewind();
		kfg.JumpToKey("text");
		kfg.GotoFirstSubKey();
		us = true;
		return;
	}
	if(kfg.GotoNextKey()) return;
	else
	{
		kfg.Rewind();
		kfg.JumpToKey("text");
		kfg.GotoFirstSubKey();
		return;
	}
}