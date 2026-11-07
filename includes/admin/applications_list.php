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
                                elections.status AS election_status,
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
                                        elections.status AS election_status,
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
                    $electionStatusLabel = $candidate['election_status'] === 'completed'
                        ? 'Election Completed'
                        : ucfirst($candidate['status']);
                    echo ' <tr>
                                    <td class="px-6 py-4 font-semibold text-slate-800">' . cleanText((string) $candidate['username']) . '</td>
                                    <td class="px-6 py-4 text-slate-600">' . cleanText((string) $candidate['election']) . '</td>
                                    <td class="px-6 py-4 text-slate-600">' . cleanText((string) $candidate['position']) . '</td>
                                    <td class="px-6 py-4"><span
                                            class="inline-flex rounded-full px-2.5 py-1 text-xs font-bold ' . ($candidate['election_status'] === 'completed' ? 'bg-slate-800 text-white' : $statusColors[$candidate['status']]) . '">' . cleanText($electionStatusLabel) . '</span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-2">
                                        <a href="../../views/admin/application_details.php?candidate='
                        . $candidate['candidate_id'] . '" class="font-semibold text-blue-700 hover:underline">View</a>';
                    if ($candidate['election_status'] !== 'completed' && $candidate['status'] === 'pending') {
                        echo '<a href="../../app/controllers/actions.php?candidate=' . $candidate['candidate_id'] . '&approve=true&csrf_token=' . urlencode(getCsrfToken()) . '"
                                                    class="rounded-lg bg-green-50 px-2.5 py-1.5 text-xs font-bold text-green-700">Approve</a>
                                                    <a href="../../app/controllers/actions.php?candidate=' . $candidate['candidate_id'] . '&approve=false&csrf_token=' . urlencode(getCsrfToken()) . '"
                                                    class="rounded-lg bg-red-50 px-2.5 py-1.5 text-xs font-bold text-red-700">Reject</a>';
                    } elseif ($candidate['election_status'] !== 'completed' && $candidate['status'] !== 'pending') {
                        echo '<span class="text-xs font-bold text-slate-500">Already reviewed</span>';
                    }
                    if ($candidate['election_status'] === 'completed') {
                        echo '<a href="../../app/controllers/actions.php?delete_application=' . $candidate['candidate_id'] . '&csrf_token=' . urlencode(getCsrfToken()) . '" onclick="return confirm(\'Delete this completed election application?\')" class="rounded-lg bg-red-50 px-2.5 py-1.5 text-xs font-bold text-red-700">Delete</a>';
                    }
                    echo ' </div>
                                    </td>
                                 </tr>';
                }
            } ?>

      </tbody>
  </table>