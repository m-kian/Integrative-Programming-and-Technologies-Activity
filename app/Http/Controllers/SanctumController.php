<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SanctumController extends Controller
{
    /**
     * Create a new API token for the authenticated user.
     */
    public function createToken(Request $request)
    {
        $request->validate([
            'token_name' => 'required|string'
        ]);

        $token = $request->user()->createToken($request->token_name);

        return response()->json(['token' => $token->plainTextToken]);
    }

    /**
     * Create a new API token with specific abilities.
     */
    public function createTokenWithAbilities(Request $request)
    {
        $request->validate([
            'token_name' => 'required|string',
            'abilities' => 'array'
        ]);

        $token = $request->user()->createToken(
            $request->token_name,
            $request->abilities ?? []
        );

        return response()->json(['token' => $token->plainTextToken]);
    }

    /**
     * Get all tokens for the authenticated user.
     */
    public function getTokens(Request $request)
    {
        return response()->json(['tokens' => $request->user()->tokens]);
    }

    /**
     * Revoke a specific token.
     */
    public function revokeToken(Request $request)
    {
        $request->validate([
            'token_id' => 'required|integer'
        ]);

        $token = $request->user()->tokens()->find($request->token_id);

        if (!$token) {
            return response()->json(['error' => 'Token not found'], 404);
        }

        $token->delete();

        return response()->json(['message' => 'Token revoked successfully']);
    }

    /**
     * Revoke all tokens for the authenticated user.
     */
    public function revokeAllTokens(Request $request)
    {
        $request->user()->tokens()->delete();

        return response()->json(['message' => 'All tokens revoked successfully']);
    }

    /**
     * Check if token has a specific ability.
     */
    public function checkAbility(Request $request)
    {
        $request->validate([
            'ability' => 'required|string'
        ]);

        $hasAbility = $request->user()->tokenCan($request->ability);

        return response()->json([
            'ability' => $request->ability,
            'has_ability' => $hasAbility
        ]);
    }

    /**
     * Example: Update server with authorization checks.
     */
    public function updateServer(Request $request, $serverId)
    {
        $server = (object)[
            'id' => $serverId,
            'user_id' => auth()->id()
        ];

        if ($request->user()->id === $server->user_id &&
            $request->user()->tokenCan('server:update')) {
            return response()->json(['message' => 'Server updated successfully']);
        }

        return response()->json([
            'error' => 'Unauthorized - insufficient permissions or invalid token ability'
        ], 403);
    }

    /**
     * Example: Delete server with authorization checks.
     */
    public function deleteServer(Request $request, $serverId)
    {
        $server = (object)[
            'id' => $serverId,
            'user_id' => auth()->id()
        ];

        if ($request->user()->id === $server->user_id &&
            $request->user()->tokenCan('server:delete')) {
            return response()->json(['message' => 'Server deleted successfully']);
        }

        return response()->json([
            'error' => 'Unauthorized - insufficient permissions or invalid token ability'
        ], 403);
    }
}
