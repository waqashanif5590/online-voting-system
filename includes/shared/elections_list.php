 <table class="w-full min-w-[520px] text-left text-sm">
     <thead class="bg-slate-50 text-xs uppercase tracking-wide text-slate-500">
         <tr>
             <th scope="col" class="px-5 py-3 font-semibold">Election Title</th>
             <th scope="col" class="px-5 py-3 font-semibold">Start Date</th>
             <th scope="col" class="px-5 py-3 font-semibold">End Date</th>
             <th scope="col" class="px-5 py-3 font-semibold">Status</th>
         </tr>
     </thead>
     <tbody class="divide-y divide-slate-100">

         <?php
            $elections = $pdo->query("SELECT * FROM elections LIMIT 4")->fetchAll();
            $statusColors = [
                'upcoming' => 'bg-blue-50 text-blue-700',
                'ongoing' => 'bg-green-50 text-green-700',
                'completed' => 'bg-slate-100 text-slate-600'
            ];
            foreach ($elections as $key => $election) {
                echo '
                                          <tr>
                                        <td class="px-5 py-4 font-semibold text-slate-800">' . $election['title'] . '</td>
                                        <td class="px-5 py-4 text-slate-600">' . date('M j, Y - h:i A', strtotime($election['start_date']))  . '</td>
                                        <td class="px-5 py-4 text-slate-600">' . date('M j, Y - h:i A', strtotime($election['end_date'])) . '</td>
                                        <td class="px-5 py-4"><span
                                                class="rounded-full px-2.5 py-1 text-xs font-bold 
                                                ' . $statusColors[$election['status']] . '">' . ucfirst($election['status']) . '</span>
                                        </td>
                                    </tr>';
            }
            ?>
     </tbody>
 </table>