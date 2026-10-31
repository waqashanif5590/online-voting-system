  <table class="w-full min-w-[760px] text-left text-sm">
      <thead class="bg-slate-50 text-xs uppercase tracking-wide text-slate-500">
          <tr>
              <th scope="col" class="px-6 py-3 font-semibold">Candidate Name</th>
              <th scope="col" class="px-6 py-3 font-semibold">Election</th>
              <th scope="col" class="px-6 py-3 font-semibold">Position</th>
              <th scope="col" class="px-6 py-3 font-semibold">Application Status</th>
              <th scope="col" class="px-6 py-3 font-semibold">Actions</th>
          </tr>
      </thead>
      <tbody class="divide-y divide-slate-100">
          <?php
            $statusColors = [
                'pending' => 'bg-gray-100 text-gray-700',
                'approved' => 'bg-green-50 text-green-700',
                'rejected' => 'bg-red-50 text-red-700'
            ];
            if ($limit == false) {
                $candidates = $pdo->query("SELECT applications.status AS status, 
                                applications.id AS candidate_id, 
                                users.name AS username,
                                elections.title AS election,
                                positions.title AS position FROM applications
                                INNER JOIN users ON applications.user_id=users.id
                                INNER JOIN elections ON applications.election_id=elections.id
                                INNER JOIN positions ON applications.position_id=positions.id
                                ")->fetchAll();
            } else {

                $candidates = $pdo->query("SELECT applications.status AS status, 
                                        applications.id AS candidate_id, 
                                        users.name AS username,
                                        elections.title AS election,
                                        positions.title AS position FROM applications
                                        INNER JOIN users ON applications.user_id=users.id
                                        INNER JOIN elections ON applications.election_id=elections.id
                                        INNER JOIN positions ON applications.position_id=positions.id
                                        LIMIT 4
                                        ")->fetchAll();
            }
            if (empty($candidates)) {
                echo '<tr>
                                <td class="px-6 py-4 font-semibold text-slate-600">
                                No Application submitted by candidates
                                </td>
                                </tr>';
            } else {

                foreach ($candidates as $key => $candidate) {
                    echo ' <tr>
                                    <td class="px-6 py-4 font-semibold text-slate-800">' . $candidate['username'] . '</td>
                                    <td class="px-6 py-4 text-slate-600">' . $candidate['election'] . '</td>
                                    <td class="px-6 py-4 text-slate-600">' . $candidate['position'] . '</td>
                                    <td class="px-6 py-4"><span
                                            class="inline-flex rounded-full px-2.5 py-1 text-xs font-bold ' . $statusColors[$candidate['status']] . '">' . ucfirst($candidate['status']) . '</span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-2">
                                        <a href="../../views/admin/application_details.php?candidate='
                        . $candidate['candidate_id'] . '" class="font-semibold text-blue-700 hover:underline">View</a>';
                    if ($candidate['status'] === 'pending') {
                        echo '<a href="../../app/controllers/actions.php?candidate=' . $candidate['candidate_id'] . '&approve=true"
                                                    class="rounded-lg bg-green-50 px-2.5 py-1.5 text-xs font-bold text-green-700">Approve</a>
                                                    <a href="../../app/controllers/actions.php?candidate=' . $candidate['candidate_id'] . '&approve=false"
                                                    class="rounded-lg bg-red-50 px-2.5 py-1.5 text-xs font-bold text-red-700">Reject</a>';
                    }
                    echo ' </div>
                                    </td>
                                 </tr>';
                }
            } ?>

      </tbody>
  </table>