 <table class="w-full min-w-[780px] text-left text-sm">
     <thead class="bg-slate-50 text-xs uppercase tracking-wide text-slate-500">
         <tr>
             <th scope="col" class="px-5 py-3 font-semibold">Voter Name</th>
             <th scope="col" class="px-5 py-3 font-semibold">Identity Number</th>
             <th scope="col" class="px-5 py-3 font-semibold">Election</th>
             <th scope="col" class="px-5 py-3 font-semibold">Token Status</th>
             <th scope="col" class="px-5 py-3 font-semibold">Account Status</th>
             <th scope="col" class="px-5 py-3 font-semibold">Actions</th>
         </tr>
     </thead>
     <tbody class="divide-y divide-slate-100">
         <?php
            $statusColors = [
                true => 'bg-green-50 text-green-700',
                false => 'bg-red-50 text-red-700'
            ];
            $token = 0;
            $token_status = '';
            if ($limit == false) {
                $voters = $pdo->query("SELECT voters.token AS assigned_token,
                                                    voters.has_voted AS vote_status,
                                                    users.id AS user_id,
                                                    users.name AS username,
                                                    users.identity_number AS user_identity,
                                                    users.status AS account_status,
                                                    elections.title AS election
                                                FROM users
                                                LEFT JOIN voters ON voters.user_id = users.id
                                                LEFT JOIN elections ON voters.election_id = elections.id
                                                WHERE users.role = 'voter'
                                            ")->fetchAll(PDO::FETCH_ASSOC);
            } else {

                $voters = $pdo->query("SELECT voters.token AS assigned_token,
                                                    voters.has_voted AS vote_status,
                                                    users.id AS user_id,
                                                    users.name AS username,
                                                    users.identity_number AS user_identity,
                                                    users.status AS account_status,
                                                    elections.title AS election
                                                FROM users
                                                LEFT JOIN voters ON voters.user_id = users.id
                                                LEFT JOIN elections ON voters.election_id = elections.id
                                                WHERE users.role = 'voter'
                                                LIMIT 4
                                            ")->fetchAll(PDO::FETCH_ASSOC);
            }
            if (empty($voters)) {
                echo ' <tr>
                                        <td class="px-5 py-4 font-semibold text-slate-800">No Voters Exists as no election is completed.</td>
                                        </tr>';
            } else {
                foreach ($voters as $key => $voter) {
                    if ($voter['assigned_token'] !== null && $voter['vote_status'] == false) {
                        $token = 1;
                        $token_status = 'Assigned';
                    } else if ($voter['assigned_token'] == null) {
                        $token = 0;
                        $token_status = 'Not Assigned';
                    } else if ($voter['vote_status'] == true) {
                        $token = 1;
                        $token_status = 'Token Used';
                    }
                    if ($voter['account_status'] == true) {
                        $btn_text = 'Block';
                        $status_text = 'Active';
                    } else {
                        $btn_text = 'Unblock';
                        $status_text = 'Blocked';
                    }

                    echo '
                                           <tr>
                                        <td class="px-5 py-4 font-semibold text-slate-800">' . $voter['username'] . '</td>
                                        <td class="px-5 py-4 text-slate-600">' . $voter['user_identity'] . '</td>
                                        <td class="px-5 py-4 text-slate-600">' . $voter['election'] . '</td>
                                        <td class="px-5 py-4"><span
                                                class="rounded-full px-2.5 py-1 text-xs font-bold 
                                                ' . $statusColors[$token] . '">
                                                ' . $token_status . '</span>
                                        </td>
                                        <td class="px-5 py-4"><span
                                                class="rounded-full px-2.5 py-1 text-xs font-bold
                                                ' . $statusColors[$voter['account_status']] . '">
                                                ' . $status_text . '
                                                </span>
                                        </td>
                                        <td class="px-5 py-4">
                                            <div class="flex items-center gap-2"><a href="#voters"
                                                    class="font-semibold text-blue-700 hover:underline">View</a>
                                                    <a href="../../app/controllers/actions.php?voter=' . $voter['user_id'] . '&csrf_token=' . urlencode(getCsrfToken()) . '"
                                                    class="rounded-lg bg-red-50 px-2.5 py-1.5 text-xs font-bold text-red-700">'
                        . $btn_text .
                        '
                                                    </a>';
                    if ($token == false) {
                        echo '<a href="assign_token.php?user=' . $voter['user_id'] . '" class="rounded-lg bg-blue-50 px-2.5 py-1.5 text-xs font-bold text-blue-700">Assign Token</a>';
                    }
                    echo '
                                            </div>
                                        </td>
                                    </tr>';
                }
            }
            ?>

     </tbody>
 </table>