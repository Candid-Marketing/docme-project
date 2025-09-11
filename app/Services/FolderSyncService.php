<?php

namespace App\Services;

use App\Models\AdminFolderTemplate;
use App\Models\UserStructureFolder;

class FolderSyncService
{
    /**
     * Sync admin folder templates to a specific user.
     */
    public function syncAdminFoldersToUser(int $userId): void
    {
        $templates = AdminFolderTemplate::all();
        $folderMap = [];

        foreach ($templates->sortBy('sort_order') as $template) {
            $parentId = null;

            if ($template->parent_code && isset($folderMap[$template->parent_code])) {
                $parentId = $folderMap[$template->parent_code];
            }

            $existing = UserStructureFolder::where('user_id', $userId)
                ->where('linked_admin_code', $template->unique_code)
                ->first();

            if (!$existing) {
                $folder = UserStructureFolder::create([
                    'user_id'           => $userId,
                    'folder_name'       => $template->name,
                    'parent_id'         => $parentId,
                    'linked_admin_code' => $template->unique_code,
                ]);

                $folderMap[$template->unique_code] = $folder->id;
            } else {
                $folderMap[$template->unique_code] = $existing->id;
            }
        }
    }
}
