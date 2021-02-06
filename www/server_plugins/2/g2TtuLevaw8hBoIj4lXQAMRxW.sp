#include <sourcemod>

public Plugin myinfo =
{
	name = "ACP | Usun Wiadomosci o Kasie",
	author = "EMCE",
	version = "1.0"
};

public OnPluginStart()
{
	HookUserMessage(GetUserMessageId("TextMsg"), MsgHook_AdjustMoney, true);
}

public Action:MsgHook_AdjustMoney(UserMsg:msg_id, Handle:msg, const players[], playersNum, bool:reliable, bool:init)
{
	decl String:buffer[64];
	PbReadString(msg, "params", buffer, sizeof(buffer), 0);
	
	if (StrEqual(buffer, "#Player_Cash_Award_Killed_Enemy"))
	{
		return Plugin_Handled;
	}
	if (StrEqual(buffer, "#Team_Cash_Award_Win_Hostages_Rescue"))
	{
		return Plugin_Handled;
	}
	if (StrEqual(buffer, "#Team_Cash_Award_Win_Defuse_Bomb"))
	{
		return Plugin_Handled;
	}
	if (StrEqual(buffer, "#Team_Cash_Award_Win_Time"))
	{
		return Plugin_Handled;
	}
	if (StrEqual(buffer, "#Team_Cash_Award_Elim_Bomb"))
	{
		return Plugin_Handled;
	}
	if (StrEqual(buffer, "#Team_Cash_Award_Elim_Hostage"))
	{
		return Plugin_Handled;
	}
	if (StrEqual(buffer, "#Team_Cash_Award_T_Win_Bomb"))
	{
		return Plugin_Handled;
	}
	if (StrEqual(buffer, "#Player_Point_Award_Assist_Enemy_Plural"))
	{
		return Plugin_Handled;
	}
	if (StrEqual(buffer, "#Player_Point_Award_Assist_Enemy"))
	{
		return Plugin_Handled;
	}
	if (StrEqual(buffer, "#Player_Point_Award_Killed_Enemy_Plural"))
	{
		return Plugin_Handled;
	}
	if (StrEqual(buffer, "#Player_Point_Award_Killed_Enemy"))
	{
		return Plugin_Handled;
	}
	if (StrEqual(buffer, "#Player_Cash_Award_Kill_Hostage"))
	{
		return Plugin_Handled;
	}
	if (StrEqual(buffer, "#Player_Cash_Award_Damage_Hostage"))
	{
		return Plugin_Handled;
	}
	if (StrEqual(buffer, "#Player_Cash_Award_Get_Killed"))
	{
		return Plugin_Handled;
	}
	if (StrEqual(buffer, "#Player_Cash_Award_Respawn"))
	{
		return Plugin_Handled;
	}
	if (StrEqual(buffer, "#Player_Cash_Award_Interact_Hostage"))
	{
		return Plugin_Handled;
	}
	if (StrEqual(buffer, "#Player_Cash_Award_Killed_Enemy"))
	{
		return Plugin_Handled;
	}
	if (StrEqual(buffer, "#Player_Cash_Award_Rescued_Hostage"))
	{
		return Plugin_Handled;
	}
	if (StrEqual(buffer, "#Player_Cash_Award_Bomb_Defused"))
	{
		return Plugin_Handled;
	}
	if (StrEqual(buffer, "#Player_Cash_Award_Bomb_Planted"))
	{
		return Plugin_Handled;
	}
	if (StrEqual(buffer, "#Player_Cash_Award_Killed_Enemy_Generic"))
	{
		return Plugin_Handled;
	}
	if (StrEqual(buffer, "#Player_Cash_Award_Killed_VIP"))
	{
		return Plugin_Handled;
	}
	if (StrEqual(buffer, "#Player_Cash_Award_Kill_Teammate"))
	{
		return Plugin_Handled;
	}
	if (StrEqual(buffer, "#Team_Cash_Award_Win_Hostage_Rescue"))
	{
		return Plugin_Handled;
	}
	if (StrEqual(buffer, "#Team_Cash_Award_Loser_Bonus"))
	{
		return Plugin_Handled;
	}
	if (StrEqual(buffer, "#Team_Cash_Award_Loser_Zero"))
	{
		return Plugin_Handled;
	}
	if (StrEqual(buffer, "#Team_Cash_Award_Rescued_Hostage"))
	{
		return Plugin_Handled;
	}
	if (StrEqual(buffer, "#Team_Cash_Award_Hostage_Interaction"))
	{
		return Plugin_Handled;
	}
	if (StrEqual(buffer, "#Team_Cash_Award_Hostage_Alive"))
	{
		return Plugin_Handled;
	}
	if (StrEqual(buffer, "#Team_Cash_Award_Planted_Bomb_But_Defused"))
	{
		return Plugin_Handled;
	}
	if (StrEqual(buffer, "#Team_Cash_Award_CT_VIP_Escaped"))
	{
		return Plugin_Handled;
	}
	if (StrEqual(buffer, "#Team_Cash_Award_T_VIP_Killed"))
	{
		return Plugin_Handled;
	}
	if (StrEqual(buffer, "#Team_Cash_Award_no_income"))
	{
		return Plugin_Handled;
	}
	if (StrEqual(buffer, "#Team_Cash_Award_Generic"))
	{
		return Plugin_Handled;
	}
	if (StrEqual(buffer, "#Team_Cash_Award_Custom"))
	{
		return Plugin_Handled;
	}
	if (StrEqual(buffer, "#Team_Cash_Award_no_income_suicide"))
	{
		return Plugin_Handled;
	}
	if (StrEqual(buffer, "#Player_Cash_Award_ExplainSuicide_YouGotCash"))
	{
		return Plugin_Handled;
	}
	if (StrEqual(buffer, "#Player_Cash_Award_ExplainSuicide_Spectators	"))
	{
		return Plugin_Handled;
	}
	if (StrEqual(buffer, "#Player_Cash_Award_ExplainSuicide_EnemyGotCash"))
	{
		return Plugin_Handled;
	}
	if (StrEqual(buffer, "#Player_Cash_Award_ExplainSuicide_TeammateGotCash"))
	{
		return Plugin_Handled;
	}
	return Plugin_Continue;
} 